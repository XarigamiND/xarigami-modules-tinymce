(function(){tinymce.PluginManager.requireLangPack('dphighlight');tinymce.create('tinymce.plugins.dpHighlight',{init:function(ed,url){var cls="dpcode";ed.addCommand('mceadddpHighlight',function(){ed.windowManager.open({file:url+'/dphighlight.htm',width:530+ed.getLang('dphighlight.delta_width',0),height:560+ed.getLang('dphighlight.delta_height',0),inline:1},{plugin_url:url,some_custom_arg:'custom arg'})});ed.addButton("dphighlight",{title:"dphighlight.desc",cmd:"mceadddpHighlight",image:url+"/img/dphighlight.gif"});ed.onInit.add(function(){if(ed.settings.content_css!==false)ed.dom.loadCSS(url+"/css/dphighlight.css");if(ed.theme.onResolveName){ed.theme.onResolveName.add(function(th,o){if(o.node.nodeName=='PRE'&&ed.dom.hasClass(o.node,cls))o.name='dphighlight'})}});ed.onClick.add(function(ed,e){e=e.target;if(e.nodeName==='PRE'&&ed.dom.hasClass(e,cls))ed.selection.select(e)});ed.onNodeChange.add(function(ed,cm,n){cm.setActive('dphighlight',n.nodeName=='PRE'&&ed.dom.hasClass(n,cls))})},getInfo:function(){return{longname:'dpHighlight',author:'Xarigami',authorurl:'http://xarigami.com/',infourl:'http://xarigami.com/project/xarigami_dphighlight',version:"1.2"}}});tinymce.PluginManager.add('dphighlight',tinymce.plugins.dpHighlight)})();