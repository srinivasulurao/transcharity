<?php
/* Template Name: Test */

get_header();
?>

<select name="filter[item_category]">
	<option value="">Category</option>
	<option value="airport"> airport</option><option value="Asia">- Asia</option><option value="First Class">-- First Class</option><option value="Second Class">-- Second Class</option><option value="North America">- North America</option><option value="First Class">-- First Class</option><option value="Second Class">-- Second Class</option><option value="South America">- South America</option><option value="First Class">-- First Class</option><option value="VIP">--- VIP</option><option value="Second Class">-- Second Class</option><option value="Subscribe">- Subscribe</option><option value="Books"> Books</option><option value="Coffee"> Coffee</option><option value="Drink &amp; Beer"> Drink &amp; Beer</option><option value="Fancy / Toy"> Fancy / Toy</option><option value="hotel"> hotel</option><option value="1 Star">- 1 Star</option><option value="2 Star">- 2 Star</option><option value="3 Star">- 3 Star</option><option value="4 Star">- 4 Star</option><option value="5 Star">- 5 Star</option><option value="Real Estate"> Real Estate</option><option value="restaurant"> restaurant</option><option value="Korea Food">- Korea Food</option><option value="gangwon-do">-- gangwon-do</option><option value="Kyeonggi-do">-- Kyeonggi-do</option><option value="Seoul">-- Seoul</option><option value="Western Food">- Western Food</option><option value="Sports"> Sports</option><option value="Table &amp; Chear"> Table &amp; Chear</option><option value="Teaching"> Teaching</option><option value="Vegetable"> Vegetable</option><option value="[Korean] 5 Star"> [Korean] 5 Star</option><option value="[Korean] restaurant"> [Korean] restaurant</option><option value="[Korean] Korea Food">- [Korean] Korea Food</option><option value="[Korean] Seoul">-- [Korean] Seoul</option><option value="[Korean] Western Food">- [Korean] Western Food</option></select>


<select name="filter[item_category]">
	<option value=""> Category</option>
	<option value=""> C ategory</option>
	<option value=""> Ca tegory</option>
	<option value=""> Cat egory</option>
	<option value=""> Cate gory</option>
	<option value=""> Categ ory</option>
	<option value=""> Catego ry</option>
	<option value=""> Categor y</option>
	<option value=""> Category </option>
</select>






<select name="filter[item_category]">
	<option value=""><?php _e('Category', 'javo_fr');?></option>
	<?php echo apply_filters('javo_get_selbox_child_term_lists', 'item_category', null, 'select', null, 0, 0, "-");?>
</select>
<script>
jQuery(function($){

	$("select").chosen();

});

</script>
<?php
get_footer();