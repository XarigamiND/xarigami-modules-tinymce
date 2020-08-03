/**
 * @author Jo Dalle Nogare based on original
 * codehighlighting  by Nawaf M Al Badia
 * @version 1.2 25 Sept 2010
 */

(function() {
    // Load plugin specific language pack
    tinymce.PluginManager.requireLangPack('dphighlight');

    tinymce.create('tinymce.plugins.dpHighlight', {
        init : function(ed, url) {
            var cls = "dpcode";
            // Register commands
            ed.addCommand('mceadddpHighlight', function() {
                ed.windowManager.open({
                    file : url + '/dphighlight.htm',
                    width : 530 + ed.getLang('dphighlight.delta_width', 0),
                    height : 560 + ed.getLang('dphighlight.delta_height', 0),
                    inline : 1
                }, {
                    plugin_url : url, // Plugin absolute URL
                    some_custom_arg : 'custom arg' // Custom argument
                });
            });

            // Register button
            ed.addButton("dphighlight", {
                title : "dphighlight.desc",
                cmd : "mceadddpHighlight",
                image : url + "/img/dphighlight.gif"
            });

            ed.onInit.add(function() {
                if (ed.settings.content_css !== false)
                    ed.dom.loadCSS(url + "/css/dphighlight.css");

                if (ed.theme.onResolveName) {
                    ed.theme.onResolveName.add(function(th, o) {
                        if (o.node.nodeName == 'PRE' && ed.dom.hasClass(o.node, cls))
                            o.name = 'dphighlight';
                    });
                }
            });
             ed.onClick.add(function(ed, e) {
                e = e.target;

                if (e.nodeName === 'PRE' && ed.dom.hasClass(e, cls))
                    ed.selection.select(e);
            });



            // Add a node change handler, selects the button in the UI when pre dp class is selected
            ed.onNodeChange.add(function(ed, cm, n) {
                cm.setActive('dphighlight', n.nodeName == 'PRE' && ed.dom.hasClass(n, cls));
            });
        },
            /**
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
            return {
                longname : 'dpHighlight',
                author : 'Xarigami',
                authorurl : 'http://xarigami.com/',
                infourl : 'http://xarigami.com/project/xarigami_dphighlight',
                version : "1.2"
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add('dphighlight', tinymce.plugins.dpHighlight);
})();