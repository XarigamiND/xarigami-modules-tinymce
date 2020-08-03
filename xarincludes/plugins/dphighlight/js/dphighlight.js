
     function  Save_Button_onclick() {
     var lang = document.getElementById("ProgrammingLangauges").value;
     var code =  WrapCode(lang);
     var fixedcode ='';
     fixedcode =  document.getElementById("CodeArea").value;
     if (lang == 'xml') {
        fixedcode = htmlEntity(fixedcode);
     }
     code = code +fixedcode;
     //code = code + "</pre> "
     if (document.getElementById("CodeArea").value == ''){
        tinyMCEPopup.close();
        return false;
    }
    tinyMCEPopup.execCommand('mceInsertContent', false, code);
    tinyMCEPopup.close();
    }

    function  WrapCode(lang)
    {
       var options = "";
       var titleoption = "";
       if (document.getElementById("autolinks").checked == true)
       options = "auto-links:true;";

       if (document.getElementById("gutter").checked == true)
       options = "gutter:false;";

       if (document.getElementById("htmlscript").checked == true)
       options = "htmlscript:true;";

       if (document.getElementById("collapse").checked == true)
       options = options + "collapse:true;";

       if (document.getElementById("toolbar").checked == true)
       options = options + "toolbar:true;";

       if (document.getElementById("firstline").value !='')
       options = options + "first-line:" + firstline.value +";";

        if (document.getElementById("highlight").value !='')
       options = options + "highlight:" + highlight.value +";";

        if (document.getElementById("codetitle").value !='')
       titleoption= " title='" + codetitle.value +"'";

       return "<pre class='brush:"+lang+";"+options+" dpcode'"+titleoption+" >";
    }

    function Cancel_Button_onclick()
    {
            tinyMCEPopup.close();
            return false;
    }


  function d2h(d) {return d.toString(16);}

    function htmlEntity(text) {
        var aa = text;
        var bb = '';
        var tags =1;
        for(i=0; i<aa.length; i++)
        {
            if(aa.charCodeAt(i)>127)
                bb += '&#x' + d2h(aa.charCodeAt(i)) + ';';
        else
          if(tags==1)
          {
            if(aa.charAt(i)=='<')
                    bb += '&lt;';
                else
                if(aa.charAt(i)=='>')
                    bb += '&gt;';
                else
                if(aa.charAt(i)=='&')
                    bb += '&amp;';
              else
            bb += aa.charAt(i);
          }
          else
          if(tags==2)
          {
            if(aa.charAt(i)=='<')
                    bb += '&lt;';
                else
                if(aa.charAt(i)=='>')
                    bb += '&gt;';
                else
            bb += aa.charAt(i);
          }
          else
          bb += aa.charAt(i);
          }
        return bb;
      }




tinyMCEPopup.requireLangPack('dphighlight');
