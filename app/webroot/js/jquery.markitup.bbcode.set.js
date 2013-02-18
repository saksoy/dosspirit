/* Copyright (C) 2008 Jay Salvat
http://markitup.jaysalvat.com
Edited by bakkelun for The DOS Spirit */

myBbcodeSettings = {
    nameSpace: 'bbcode',
	previewParserPath:	'/ajax/preview', // path to your BBCode parser
	markupSet: [
		{name:'Save', key: 'S', className:'save', beforeInsert:function(markItUp) { miu.save(markItUp); } },
        {name:'Load', className:'load', beforeInsert:function(markItUp) { miu.load(markItUp); } },
		{name:'Bold', className: 'bold', key:'B', openWith:'[b]', closeWith:'[/b]'},
		{name:'Italic', className: 'italic', key:'I', openWith:'[i]', closeWith:'[/i]'},
		{name:'Underline', className: 'underline', key:'U', openWith:'[u]', closeWith:'[/u]'},
		{separator:'---------------' },
		{name:'Picture', className: 'picture', key:'P', replaceWith:'[img][![Url]!][/img]'},
		{name:'Youtube', className: 'youtube', key:'Y', replaceWith:'[yt][![Url]!][/yt]'},
		{name:'Link', className: 'link', key:'L', openWith:'[url="[![Url]!]"]', closeWith:'[/url]', placeHolder:'Link text / Link tekst'},
		{separator:'---------------' },
		{name:'Size', className: 'font', key:'F', openWith:'[size=[![Text size]!]]', closeWith:'[/size]',
		dropMenu :[
	        {name:'Huge', openWith:'[size=25]', closeWith:'[/size]' },
			{name:'Big', openWith:'[size=20]', closeWith:'[/size]' },
			{name:'Normal (Medium)', openWith:'[size=15]', closeWith:'[/size]' },
			{name:'Small', openWith:'[size=12]', closeWith:'[/size]' },
			{name:'Tiny', openWith:'[size=10]', closeWith:'[/size]' }
		]},
		{separator:'---------------' },
		{name:'Quotes', className: 'quote', openWith:'[quote]', closeWith:'[/quote]'},
		{name:'Code', className: 'code', openWith:'[code]', closeWith:'[/code]'},
		{separator:'---------------' },
		{name:'Clean', className:'clean', replaceWith:function(markitup) { return markitup.selection.replace(/\[(.*?)\]/g, ""); } },
		{name:'Preview', className:'preview', call:'previewModal' },
		    {name:'Colors',
		               className:'colors',
		               openWith:'[color=[![Color]!]]',
		               closeWith:'[/color]',
		                    dropMenu: [
		                        {name:'Yellow', openWith:'[color=yellow]', closeWith:'[/color]', className:"yellow" },
		                        {name:'Orange', openWith:'[color=orange]', closeWith:'[/color]', className:"orange" },
		                        {name:'Red',    openWith:'[color=red]', closeWith:'[/color]', className:"red" },

		                        {name:'Blue',   openWith:'[color=blue]', closeWith:'[/color]', className:"blue" },
		                        {name:'Purple', openWith:'[color=purple]', closeWith:'[/color]', className:"purple" },
		                        {name:'Green',  openWith:'[color=green]', closeWith:'[/color]', className:"green" },

		                        {name:'White',  openWith:'[color=white]', closeWith:'[/color]', className:"white" },
		                        {name:'Gray',   openWith:'[color=gray]', closeWith:'[/color]', className:"gray" },
		                        {name:'Black',  openWith:'[color=black]', closeWith:'[/color]', className:"black" }
		                    ]
                    }
		]
};

// mIu nameSpace to avoid conflict.
miu = {
    save: function(markItUp) {
        var data = encodeURIComponent(markItUp.textarea.value); // Thx Gregory LeRoy
        /*var ok = confirm("Save the content?");
        if (!ok) {
            return false;
        }*/
        $.post("/admin/editor/save", "data="+data, function(response) {
                if(response === "MIU:OK") {
                    alert("Saved!");
                }
            }
        );
    },

    load: function(markItUp) {
        $.get("/admin/editor/load", function(response) {
                if(response === "MIU:EMPTY") {
                    alert("Nothing to load");
                } else {
                    var ok = confirm("Load the saved content?");
                    if (!ok) {
                        return false;
                    }
                    markItUp.textarea.value = response;
                    //alert("Loaded!");
                }
            }
        );
    }
};
