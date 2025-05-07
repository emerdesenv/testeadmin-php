var vis_graphic = false;
var vis_totals  = false;
var vis_table   = false;
var editable_fc = false;
var add_fc      = false;

addAsterisk();

$.each(page_data, function(i, item) {
    editable_fc = item.alteracao == "S" ? true : false;
    add_fc      = item.inclusao == "S" ? true : false;
});