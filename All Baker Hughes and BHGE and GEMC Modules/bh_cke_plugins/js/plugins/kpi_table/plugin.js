(function($) {
    var cols = 2;
    CKEDITOR.plugins.add('kpi_table', {
        beforeInit: function (editor) {
            editor.addCommand("kpi_table_add_row_before", {
                exec: function(e) {
                    var selection = e.getSelection();
                    var selectedElement = selection.getStartElement();
                    if(selectedElement){
                        insertRow(selectedElement,'before');
                    }
                }
            });
            editor.addCommand("kpi_table_add_row_after", {
                exec: function(e) {
                    var selection = e.getSelection();
                    var selectedElement = selection.getStartElement();
                    if(selectedElement){
                        insertRow(selectedElement,'after');
                    }
                }
            });
            editor.addCommand("kpi_table_delete_row", {
                exec: function(e) {
                    var selection = e.getSelection();
                    var selectedElement = selection.getStartElement();
                    if(selectedElement){
                        deleteRow(selectedElement);
                    }
                }
            });
            editor.addCommand("kpi_table_delete", {
                exec: function(e) {
                    var selection = e.getSelection();
                    var selectedElement = selection.getStartElement();
                    if(selectedElement){
                        deleteTable(selectedElement);
                    }
                }
            });
            editor.addCommand("kpi_table_add_col_left", {
                exec: function(e) {
                    var selection = e.getSelection();
                    var selectedElement = selection.getStartElement();
                    if(selectedElement){
                        insertCol(selectedElement,'left');
                    }
                }
            });
            editor.addCommand("kpi_table_add_col_right", {
                exec: function(e) {
                    var selection = e.getSelection();
                    var selectedElement = selection.getStartElement();
                    if(selectedElement){
                        insertCol(selectedElement,'right');
                    }
                }
            });
            editor.addCommand("kpi_table_delete_col", {
                exec: function(e) {
                    var selection = e.getSelection();
                    var selectedElement = selection.getStartElement();
                    if(selectedElement){
                        deleteCol(selectedElement);
                    }
                }
            });
            if (editor.contextMenu) {
                editor.addMenuGroup('kpi_table_row',2);
                editor.addMenuGroup('kpi_table_delete_row');
                editor.addMenuGroup('kpi_table_col',2);
                editor.addMenuGroup('kpi_table_delete_col');
                editor.addMenuGroup('kpi_table_delete');
                editor.addMenuItems({
                    'kpi_table_row' :
                        {
                            label : 'Insert Row',
                            group : 'kpi_table_row',
                            order : 1,
                            getItems : function() {
                                return {
                                    kpi_table_add_row_before : CKEDITOR.TRISTATE_OFF,
                                    kpi_table_add_row_after : CKEDITOR.TRISTATE_OFF
                                };
                            }
                        },
                    'kpi_table_add_row_before':{
                        label: "Insert row before",
                        group: "kpi_table_row",
                        command: "kpi_table_add_row_before",
                        order : 1
                    },
                    'kpi_table_add_row_after':{
                        label: "Insert row after",
                        group: "kpi_table_row",
                        command: "kpi_table_add_row_after",
                        order : 2
                    },
                    'kpi_table_delete_row':{
                        label: "Delete Row",
                        group: "kpi_table_delete_row",
                        command: "kpi_table_delete_row",
                        order : 2
                    },
					'kpi_table_col':{
                        label : 'Insert Column',
                        group : 'kpi_table_col',
                        order : 3,
                        getItems : function() {
                            return {
                                kpi_table_add_col_left : CKEDITOR.TRISTATE_OFF,
                                kpi_table_add_col_right : CKEDITOR.TRISTATE_OFF
                            };
                        }
                    },
                    'kpi_table_add_col_left':{
                        label: "Insert column at left",
                        group: "kpi_table_col",
                        command: "kpi_table_add_col_left",
                        order : 1
                    },
                    'kpi_table_add_col_right':{
                        label: "Insert column at right",
                        group: "kpi_table_col",
                        command: "kpi_table_add_col_right",
                        order : 2
                    },
                    'kpi_table_delete_col':{
                        label: "Delete Column",
                        group: "kpi_table_delete_col",
                        command: "kpi_table_delete_col",
                        order : 4
                    },
                    'kpi_table_delete':{
                        label: "Delete Table",
                        group: "kpi_table_delete",
                        command: "kpi_table_delete",
                        order : 3
                    },
                });
            
                editor.contextMenu.addListener( function(element) {
                    // menu item state is resolved on commands.
                    var selectedElement = $(element.$);
                    var parent = selectedElement.closest(".bh-cke-kpi-table-wrapper");
                    if(parent.length > 0){
                        return {
                            kpi_table_row: CKEDITOR.TRISTATE_OFF,
                            kpi_table_delete_row: CKEDITOR.TRISTATE_OFF,
                            kpi_table_col: CKEDITOR.TRISTATE_OFF,
                            kpi_table_delete_col: CKEDITOR.TRISTATE_OFF,
                            kpi_table_delete: CKEDITOR.TRISTATE_OFF
                        };
                    }
                });
            }
        },
        init: function( editor )
        {
            var pluginDirectory = this.path;
            editor.addContentsCss(pluginDirectory + 'styles/kpi_table.css');
            var header_bg_class = '';
            editor.addCommand('kpi_table', new CKEDITOR.dialogCommand('kpi_table', {
				allowedContent: 'div{*}(*)'
            }));
    
            editor.ui.addButton('KpiTable', {
				label : 'KPI Table',
				toolbar : 'insert',
				command : 'kpi_table',
				icon : this.path + 'images/icon.png'
            });
            
            
            
            CKEDITOR.dialog.add('kpi_table', function (instance) {
                var rows = 1;
                var rows_invalid_erro = 'Please enter valid number of rows';
                var cols_invalid_error = 'Please enter valid number of cols';
                return {
					title : 'Add KPI Table',
					minWidth : 550,
					minHeight : 120,
					contents :[{
                    id : 'kpiTableForm',
                    expand : true,
                    elements :
                        [
                            {
                                id : 'txtRows',
                                type : 'text',
                                label : 'Number of rows:',
                                labelLayout: 'horizontal',
                                required: true,
                                validate : function () {
                                    if (!this.getValue()) {
                                        alert(rows_invalid_error);
                                        return false;
                                    }
                                    else{
                                        rows = parseInt(this.getValue());

                                        if (this.getValue().length === 0 ||  isNaN(rows))
                                        {
                                            alert(rows_invalid_error);
                                            return false;
                                        }
                                    }
                                }
                            },
						{
							id : 'txtCols',
							type : 'text',
							label : 'Number of cols:',
							labelLayout: 'horizontal',
							required: false,
							validate : function () {
								if (this.getValue()) {
									cols = parseInt(this.getValue());
	
									if (this.getValue().length === 0 ||  isNaN(cols))
									{
										alert(cols_invalid_error);
										return false;
									}
								}
							}
						},
						{
							type: 'checkbox',
							id: 'header_bg',
							label: 'Apply Header styles',
							labelLayout: 'horizontal',
							validate: function() {
								if(this.getValue())
									header_bg_class = 'bh-cke-kpi-table-header-styles';
								else
									header_bg_class = '';
							}
						},
						{
							type: 'html',
							html: '<div style="border-top: 1px solid black;margin-left:5px;margin-top: 35px;padding-top: 5px;"><p style="font-size:12px;font-weight:bold">Notes for correct styling :</p><ul><li style="font-size:12px;"><span style="font-weight:bold;font-size:12px;">Apply Header styles :</span>Header styles are applied to the First row </li><li style="font-size:12px;"><span style="font-weight:bold;font-size:12px;">Number of cols :</span> Optional(Default 2 cols)</li></ul></div>'
						},
                        ],
                    }],
                    onOk: function()
                    {
                        var content = '';
                        if(rows > 0)
                        {
                            content +='<div class="bh-cke-kpi-table"><div class="bh-cke-kpi-table-wrapper '+ header_bg_class +'">';
                            var i;
                            for(i=1; i <= rows; i++){
                                content += createRowHtml(cols);
                            }
                            content +='</div></div>';
                        }
                        var element = CKEDITOR.dom.element.createFromHtml(content);
                        var instance = this.getParentEditor();
                        instance.insertElement(element);
                    }
                }
            });
        }
    });

    function createRowHtml(cols) {
        var rowHtml = '<div class="bh-cke-kpi-table-row-wrapper">';;
        var j;
        for(j=1; j<=cols; j++){
            rowHtml += '<div class="bh-cke-kpi-table-col-wrapper bh-cke-kpi-table-col-'+j+'">';
            rowHtml += '<p class="hidden">&nbsp;</p></div>';
        }
        rowHtml += '</div>';
        return rowHtml;
    }
    
    function insertRow(selectedElement,position) {
        var element = $(selectedElement.$);
        var parent_elm;
        if(element.hasClass('bh-cke-kpi-table-row-wrapper'))
        {
            parent_elm = element;
        }else{
            parent_elm = element.closest('.bh-cke-kpi-table-row-wrapper');
        }
        if(parent_elm.length > 0){
            var rowhtml = createRowHtml(cols);
            if(position == 'before'){
                parent_elm.before(rowhtml);
            }else{
                parent_elm.after(rowhtml);
            }
        }
    }
    function deleteRow(selectedElement) {
        var element = $(selectedElement.$);
        var parent_elm;
        if(element.hasClass('bh-cke-kpi-table-row-wrapper'))
        {
            parent_elm = element;
        }else{
            parent_elm = element.closest('.bh-cke-kpi-table-row-wrapper');
        }
        if(parent_elm.length > 0){
            parent_elm.remove();
        }
    }
     /* funtion used to insert new column in existing table*/
    function insertCol(selectedElement,position) {
        var element = $(selectedElement.$);
        var table_elm;
        var parent_elm;
        if(element.hasClass('bh-cke-kpi-table-col-wrapper'))
        {
            parent_elm = element;
        }else{
            parent_elm = element.closest('.bh-cke-kpi-table-col-wrapper');
        }
        if(parent_elm.length > 0)
        {
            table_elm = parent_elm.closest('.bh-cke-kpi-table-wrapper');
            var index = parent_elm.index();
            var colindex = index+1;
            var colhtml = '<div class="bh-cke-kpi-table-col-wrapper"><p class="hidden">&nbsp;</p></div>';
 ;              
            if(table_elm.length > 0){
                
                var allIndexedCols =  table_elm.find('.bh-cke-kpi-table-row-wrapper .bh-cke-kpi-table-col-wrapper:nth-child('+colindex+')');
                
                if(allIndexedCols.length > 0){
                    if(position == 'left'){
                        allIndexedCols.before(colhtml);
                    }else{
                        allIndexedCols.after(colhtml);
                    }
                    cols++;
                }
            }
        }
    }
     /* funtion used to delete column in existing table*/
    function deleteCol(selectedElement) {
        var element = $(selectedElement.$);
        var parent_elm;
        if(element.hasClass('bh-cke-kpi-table-col-wrapper'))
        {
            parent_elm = element;
        }else{
            parent_elm = element.closest('.bh-cke-kpi-table-col-wrapper');
        }
        if(parent_elm.length > 0)
        {
            table_elm = parent_elm.closest('.bh-cke-kpi-table-wrapper');
            var index = parent_elm.index();
            if(table_elm.length > 0){
                var colindex = index+1;
                var allIndexedCols =  table_elm.find('.bh-cke-kpi-table-row-wrapper .bh-cke-kpi-table-col-wrapper:nth-child('+colindex+')');
                if(allIndexedCols.length > 0){
                    allIndexedCols.remove();
                    cols--;
                }
            }
        }
    }
    function deleteTable(selectedElement) {
        var element = $(selectedElement.$);
        var parent_elm;
        if(element.hasClass('bh-cke-kpi-table-wrapper'))
        {
            parent_elm = element;
        }else{
            parent_elm = element.closest('.bh-cke-kpi-table-wrapper');
        }
        if(parent_elm.length > 0){
            parent_elm.remove();
        }
    }
})(jQuery);

