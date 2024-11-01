jQuery.widget( "custom.betterselect",
	{

		_create: function()
		{
			//first step is to perform following steps on input
			// set its position to relative and make it readonly
			this.element.css({position:"relative"});
			this.element.attr('readonly', true);
			//wrap the input in a wrapper
			this.element.wrap('<div class="wrapper_betterselct">');
			//add the main droupdowns with checkbox
			this.add_div();
			//set listenerest to checkbox
			this.add_listener_to_cb();
			var parent = jQuery(this.element).parents('.wrapper_betterselct');
			parent.on("mouseover", function(event)
				{

					jQuery(this).find('.dropdown_betterselect').show();
				});
			parent.on("mouseout", function(event)
				{
					jQuery(this).find('.dropdown_betterselect').hide();
				});
		},

		add_div:function()
		{
			//var abutton = jQuery('<button>',{text:'test'});
			//this.element.after(abutton);
			var button = jQuery('<button>Clear Field</button>').addClass("share-juice-admin-widget-bs-clear-button");
			var field_input = this.element;
			jQuery(field_input).after(button);
			
			jQuery(this.element).siblings(".share-juice-admin-widget-bs-clear-button").click(function(event)
				{

					event.preventDefault();
					jQuery(field_input).val('');

				});


			jQuery('<div>').addClass('dropdown_betterselect').insertAfter(this.element);
			var div = jQuery('.dropdown_betterselect');
			var parent = jQuery(this.element).parents('.wrapper_betterselct');


			var left = jQuery(parent).offset().left;
			var top = jQuery(parent).height();
			div.css({left:0,top:top});

			var width = this.element.width();
			if(width <= 0)
			width = 200;
			div.css({width:width});

			var items = JSON.parse(this.options.json_str);
			var input_text = this.element.val();
			var input_arr = input_text.split(',');

			var table = jQuery('<table>');


			for(var index in items)
			{
				var tr = jQuery('<tr>');
				var cb = jQuery('<input type="checkbox" class="checkbox_betterselect"/>');
				var is_checked = input_arr.indexOf(index) != -1 ? true:false;
				if(is_checked === true)
				jQuery(cb).attr("checked","checked");

				td = jQuery('<td>').append(cb);


				td.appendTo(tr);
				jQuery('<td class="key_betterselect">').text(index).appendTo(tr);
				jQuery('<td class="value_betterselect">').text(items[index]).appendTo(tr);
				//tr.append(td);
				//tr.append(td).text(items[index]);
				//document.write( index + " : " + items[index] + "<br />");

				table.append(tr);

			}

			div.append(table);

			div.hide();

		},

		add_listener_to_cb:function()
		{
			var input_field = this;
			jQuery(".checkbox_betterselect").change(function()
				{
					//input_field.element.after('<p>Hello World</p>');
					//input_field.element.text('hello');
					var td = jQuery(this).parent();
					var key = td.siblings(".key_betterselect").text();
					var value = td.siblings(".value_betterselect").text();

					var input_text = input_field.element.val();

					if(jQuery(this).is(':checked'))
					{
						/*
						if(!input_text)
						input_field.element.val(key);
						else
						input_field.element.val(input_text +','+ key);
						*/
						if(!input_text)
						{
							input_field.element.val(key);
						}

						else
						{
							var arr = input_text.split(',');
							arr.push(key);

							input_field.element.val(arr.join(','));
						}

					}
					else
					{
						if(input_text)
						{
							var arr = input_text.split(',');
							var index = arr.indexOf(key);
							arr.splice(index,1);
							input_field.element.val(arr.join(','));
						}


					}

				});
		}
	});
