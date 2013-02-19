function add_gif_to_list(obj){

	children = jQuery("#gifpress_creation").children().length;
	
	jQuery("#gifpress_creation").append("<div id='gif_" + (children) + "' style='position:relative'><img class='gifimage' src='" + obj.src + "' /> Hundreths of a second<input value='" + obj.src + "' type='hidden' name='gifpress_picture_url_" + (children) + "'><input type='text' name='gifpress_picture_time_" + (children) + "'><span class='giflink' id='gif_up_" + (children) + "'>Move Up</span> | <span class='giflink' id='gif_down_" + (children) + "'>Move Down</span> | <span class='giflink_warn' id='gif_delete_" + (children) + "'>Delete</span>  </div>");

	jQuery("#gif_up_" + (children)).live("click",".click", function(){
	
		move_gif_up(this.parentNode);
	
	});
	
	jQuery("#gif_down_" + (children)).live("click",".click", function(){
	
		move_gif_down(this.parentNode);	
	
	});
	
	jQuery("#gif_delete_" + (children)).live("click",".click", function(){
	
		jQuery(this.parentNode).remove();
		
		reorder();
	
	});

}

function reorder(){

	jQuery("#gifpress_creation").children().each(function(i) { 
		jQuery(this).attr('id', "gif_" + i);
		jQuery(this).children().each(function(g){
		
			name = jQuery(this).attr("name");
			
			if(name!="undefined"){
			
				name_parts = name.split("_");
				name_parts.pop();
				name_parts.push(i);
				jQuery(this).attr("name", name_parts.join("_"));
			
			}
		
		});
	});
	
}

function move_gif_delete(obj){
	
	jQuery(obj).after(jQuery(obj).prev());
	
	reorder();

}

function move_gif_up(obj){
	
	jQuery(obj).after(jQuery(obj).prev());
	
	reorder();

}

function move_gif_down(obj){
	
	jQuery(obj).before(jQuery(obj).next());
	
	reorder();

}