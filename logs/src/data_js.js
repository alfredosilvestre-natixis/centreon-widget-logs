$(function () {
	$("#eventLogsTable").styleTable();
        //if (nbRows > itemsPerPage) {
	//alert('|'+nbRows+'| -- |'+itemsPerPage+'|');
            $("#pagination").pagination(nbRows, {
		    items_per_page: itemsPerPage,
			current_page: pageNumber,
			callback: paginationCallback
			}).append("<br/>");
	    //}
	
        $("#nbRows").html(nbCurrentItems+"/"+nbRows);

        $(".selection").each(function() {
		var curId = $(this).attr('id');
		if (typeof(clickedCb[curId]) != 'undefined') {
		    this.checked = clickedCb[curId];
		}
	    });
	
        var tmp = orderby.split(' ');
        var icn = 'n';
        if (tmp[1] == "DESC") {
            icn = 's';
        }
        $("[name="+tmp[0]+"]").append('<span style="position: relative; float: right;" class="ui-icon ui-icon-triangle-1-'+icn+'"></span>');
    });

function paginationCallback(page_index, jq) {
    if (page_index != pageNumber) {
	pageNumber = page_index;
	clickedCb = new Array();
	loadPage();
    }
}
