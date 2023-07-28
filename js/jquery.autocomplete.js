  jQuery( function() {
	  
	var searchItems = object_name.searchItems;
	  
    jQuery.widget( "custom.catcomplete", jQuery.ui.autocomplete, {
      _create: function() {
        this._super();
        this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );
      },
      _renderMenu: function( ul, items ) {
        var that = this,
          currentCategory = "";
        jQuery.each( items, function( index, item ) {
          var li;
          if ( item.category != currentCategory ) {
            ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
            currentCategory = item.category;
          }
          li = that._renderItemData ( ul, item );
          if ( item.category ) {
            li.attr( "aria-label", item.category + " : " + item.label);
          }
        });
      },
      _resizeMenu: function() {
		  this.menu.element.outerWidth( 320 );
	  },
      _renderItem: function( ul, item ) {
	      
	  	// example of markup
	  	// <li class="sf_lnk sf_item"><a href="http://web.kateandtoms.com/houses/pedington-manor/"><img src="http://web.kateandtoms.com/wp-content/uploads/2012/10/Pedington-View-280x280.jpg" width="50" height="50"><span class="sf_text">Pedington Manor </span><span class="sf_small">Stunning country house with home cinema, games room, children's den</span></a></li>
	    if (item.thumb) {
			return jQuery( "<li class='sf_lnk sf_item "+ item.category +"' data-house_id="+ item.house_id +" data-blog_id="+ item.blog_id +" data-post_id="+ item.post_id +">" ).append( "<a href='"+ item.url +"'><img class='ac_img' src='"+ item.thumb +"' width='50' height='50'><span class='sf_text'>" + item.label + "</span><span class='sf_small'>" + item.desc + "</span></a>" ).appendTo( ul );
	    } else {
			return jQuery( "<li class='sf_lnk sf_item "+ item.category +"'>" ).append( "<a href='"+ item.url +"'><span class='sf_text'>" + item.label + "</span><span class='sf_small'>" + item.desc + "</span></a>" ).appendTo( ul );
	    }
    }
    });
    var data = 
	    searchItems
/*
		{
			label: "anders",
			category: ""
		},
		{
		  label: "andreas",
		  category: ""
		},
		{
		  label: "antal",
		  category: ""
		},
		{
		  label: "annhhx10",
		  category: "Houses",
		  desc: "a lovely annhxx descriptions etc",
		  icon: "http://web.kateandtoms.com/wp-content/uploads/2012/10/Pedington-View-280x280.jpg"
		},
		{
		  label: "annk K12",
		  category: "Houses",
		  desc: "Nice an short descriptionsss",
		  icon: "http://web.kateandtoms.com/wp-content/uploads/2013/04/Penthouse-@-Barrington-1-280x280.jpg"
		},
		{
		  label: "annttop C13",
		  category: "Houses",
		  desc: "short descriptions etc",
		  icon: "http://web.kateandtoms.com/wp-content/uploads/2013/05/1.-Drawing-room-280x280.jpg"
		},
		{
		  label: "anders andersson",
		  category: "Location",
		  desc: "another anders short location descriptions etc"
		},
		{
		  label: "andreas andersson",
		  category: "Location",
		  desc: "what the short location descriptions etc"
		},
		{
		  label: "andreas johnson",
		  category: "Location",
		  desc: "yeah baby short location descriptions etc"
		}
*/
    ;
 
    jQuery( ".dsearch, .msearch" ).catcomplete({
      delay: 0,
      source: data,
	  position: { my : "right top", at: "right bottom" }
    });

  } );

