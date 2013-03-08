<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="utf-8">
        <title>swarm tv</title>
        <script src="<?php echo base_url(); ?>js/vendor/jquery-1.8.3.min.js"></script>
        <style type="text/css">
            html, body {
                width:100%;
                height:100%;
                padding:0px;
                overflow:hidden;
                background-color:#000022;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
                color: #ccc;
                font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
                font-size: 1em
            }
        }
        #the-swarm {}
		
        #the-swarm.linkable {
            cursor:pointer;
        }
        #arbor {
            position:absolute;
            min-height:100%;
            min-width:100%;
        }
        </style>
    </head>
    
    <body>
        <div>
            <form action="" method="get" enctype="multipart/form-data" class="hidden" id="filter_form">
                <input name="filter" value="<?php echo $filter; ?>" onchange="submit();" />
                <input type="submit" value="Filter">
            </form>
        </div>
        <canvas class="" style="opacity: 1; display: inline;" id="the-swarm" width="1680" height="428"></canvas>
        <img id="bg" src="<?php echo base_url(); ?>img/default_background.jpg" style="display:none;" />
        <script src="<?php echo base_url(); ?>libraries/arbor/lib/arbor.js"></script>
        <script src="<?php echo base_url(); ?>libraries/arbor/lib/arbor-tween.js"></script>
        <script src="<?php echo base_url(); ?>libraries/arbor/lib/arbor-graphics.js"></script>
        <script type="text/javascript">
            (function ($) {

                var Renderer = function (elt) {
                    var dom = $(elt)
                    var canvas = dom.get(0)
                    var ctx = canvas.getContext("2d");
                    var gfx = arbor.Graphics(canvas)
                    var sys = null;
                    var img = document.getElementById("bg"); //from Al's coding

                    var selected = null,
                        nearest = null,
                        _mouseP = null;


                    var that = {
                        init: function (pSystem) {
                            sys = pSystem
                            sys.screen({
                                size: {
                                    width: dom.width(),
                                    height: dom.height()
                                },
                                padding: [36, 150, 36, 150]
                            })

                            $(window).resize(that.resize)
                            that.resize()
                            that._initMouseHandling()

                        },
                        resize: function () {
                            canvas.width = $(window).width()
                            canvas.height = $(window).height()
                            sys.screen({
                                size: {
                                    width: canvas.width - 50,
                                    height: canvas.height - 50
                                }
                            })
                            that.redraw()
                        },
                        redraw: function () {
                            gfx.clear()
                            ctx.drawImage(img, 0, 0, canvas.width, canvas.height); //from Al's code
                            sys.eachEdge(function (edge, p1, p2) {
                                gfx.line(p1, p2, {
                                    stroke: "silver",
                                    width: 2
                                });
                            })
                            sys.eachNode(function (node, pt) {
                                var w = Math.max(20, 20 + gfx.textWidth(node.name))
                                gfx.rect(pt.x - w / 2, pt.y - 8, w, 20, 4, {
                                    fill: '#000022',
                                    width: 2,
									stroke: node.data.stroke
                                });
                                //gfx.rect(pt.x-w/2, pt.y-8, w, 20, 4, {});
                                //gfx.text(node.name, pt.x, pt.y+5, {color:"orange", align:"center", font:"Arial", size:12});
                                gfx.text(node.name, pt.x, pt.y + 7, {
                                    color: "orange",
                                    align: "center",
                                    font: "Arial",
                                    size: 12
                                });
                            })
                        },


                        _initMouseHandling: function () {
                            // no-nonsense drag and drop (thanks springy.js)
                            selected = null;
                            nearest = null;
                            var dragged = null;
                            var oldmass = 1

                            var _section = null

                            var handler = {
                                moved: function (e) {
                                    var pos = $(canvas).offset();
                                    _mouseP = arbor.Point(e.pageX - pos.left, e.pageY - pos.top)
                                    nearest = sys.nearest(_mouseP);

                                    if (!nearest.node) return false

                                    if (nearest.node.data.shape != 'dot') {
                                        selected = (nearest.distance < 50) ? nearest : null
                                        if (selected) {
                                            dom.addClass('linkable')
                                            window.status = selected.node.data.link.replace(/^\//, "http://" + window.location.host + "/").replace(/^#/, '')
                                        } else {
                                            dom.removeClass('linkable')
                                            window.status = ''
                                        }
                                    }

                                    return false
                                },
                                clicked: function (e) {
                                    var pos = $(canvas).offset();
                                    _mouseP = arbor.Point(e.pageX - pos.left, e.pageY - pos.top)
                                    nearest = dragged = sys.nearest(_mouseP);

                                    if (nearest && selected && nearest.node === selected.node) {
                                        var link = selected.node.data.link
                                        if (link.match(/^#/)) {
                                            $(that).trigger({
                                                type: "navigate",
                                                path: link.substr(1)
                                            })
                                        } else {
                                            window.location = link
                                        }
                                        return false
                                    }


                                    if (dragged && dragged.node !== null) dragged.node.fixed = true

                                    $(canvas).unbind('mousemove', handler.moved);
                                    $(canvas).bind('mousemove', handler.dragged)
                                    $(window).bind('mouseup', handler.dropped)

                                    return false
                                },
                                dragged: function (e) {
                                    var old_nearest = nearest && nearest.node._id
                                    var pos = $(canvas).offset();
                                    var s = arbor.Point(e.pageX - pos.left, e.pageY - pos.top)

                                    if (!nearest) return
                                    if (dragged !== null && dragged.node !== null) {
                                        var p = sys.fromScreen(s)
                                        dragged.node.p = p
                                    }

                                    return false
                                },

                                dropped: function (e) {
                                    if (dragged === null || dragged.node === undefined) return
                                    if (dragged.node !== null) dragged.node.fixed = false
                                    dragged.node.tempMass = 1000
                                    dragged = null;
                                    // selected = null
                                    $(canvas).unbind('mousemove', handler.dragged)
                                    $(window).unbind('mouseup', handler.dropped)
                                    $(canvas).bind('mousemove', handler.moved);
                                    _mouseP = null
                                    return false
                                }


                            }

                            $(canvas).mousedown(handler.clicked);
                            $(canvas).mousemove(handler.moved);

                        }
                    }

                    return that
                }

                $(document).ready(function () {

                    var links = <?php echo $links; ?> ;
					//console.log(links);
					var strokeColour
					var UIstring = '';
					var edgeString = '';
					var nodeString = '';
					//iterate through the list of pages found
					for (var i = 0; i < links.length; i++) {
						strokeColour = '';
						//check to see if any matches have links
						if (links[i].link_tree.length > 0) {
							//create a string to represent each node from which the edge is to be drawn
							edgeString = edgeString + '"' + links[i].title + '":{';
							//search through all the links tree for the page to create the connections
							for (var m = 0; m < links[i].link_tree.length; m++) {
								edgeString = edgeString + '"' + links[i].link_tree[m].pagesTitle + '":{},'
							}
							//take off end comma
							edgeString = edgeString.substr(0,edgeString.length-1);
							edgeString = edgeString + '},';
							//check to see if this node should be a hub, if so, give it a white border
							if (links[i].link_tree.length > 2) {
								strokeColour = "white";
							}
						}
						//build up the node string with title, link and stroke colour ('' will make it transparent)
						nodeString = nodeString + '"' + links[i].title;
						nodeString = nodeString + '":{"link":"<?php echo base_url(); ?>index.php/pages/view/' + links[i].title + '", ';
						nodeString = nodeString + '"stroke":"' + strokeColour + '"},';
					}
					//take off the end comas
					nodeString = nodeString.substr(0,nodeString.length-1);
					edgeString = edgeString.substr(0,edgeString.length-1);
					//construct the UIstring
					UIstring = '{"nodes":{';
					UIstring = UIstring + nodeString;
					UIstring = UIstring + '}, "edges":{';
					UIstring = UIstring + edgeString;
					UIstring = UIstring + '}}';
					var theUI = $.parseJSON(UIstring);


                    var sys = arbor.ParticleSystem();
                    sys.parameters({
                        stiffness: 900,
                        repulsion: 2000,
                        gravity: true,
                        dt: 0.015
                    })
                    sys.renderer = Renderer("#the-swarm");
                    sys.graft(theUI);

                })
            })(this.jQuery)
        </script>
    </body>

</html>