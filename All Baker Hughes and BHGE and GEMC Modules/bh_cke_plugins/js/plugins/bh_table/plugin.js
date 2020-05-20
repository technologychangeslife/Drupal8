(function($) {
    var cols = 2; var rows = 1; var header=''; var border='';var headerclass='';var borderclass='';
    CKEDITOR.plugins.add('bh_table', {
        beforeInit: function (editor) {
            editor.addCommand("bh_table_add_row_before", {
                exec: function(e) {
                    var selection = e.getSelection();
                    var selectedElement = selection.getStartElement();
                    if(selectedElement){
                        insertRow(selectedElement,'before');
                    }
                }
            });
            editor.addCommand("bh_table_add_row_after", {
                exec: function(e) {
                    var selection = e.getSelection();
                    var selectedElement = selection.getStartElement();
                    if(selectedElement){
                        insertRow(selectedElement,'after');
                    }
                }
            });
            editor.addCommand("bh_table_delete_row", {
                exec: function(e) {
                    var selection = e.getSelection();
                    var selectedElement = selection.getStartElement();
                    if(selectedElement){
                        deleteRow(selectedElement);
                    }
                }
            });
            editor.addCommand("bh_table_add_col_left", {
                exec: function(e) {
                    var selection = e.getSelection();
                    var selectedElement = selection.getStartElement();
                    if(selectedElement){
                        insertCol(selectedElement,'left');
                    }
                }
            });
            editor.addCommand("bh_table_add_col_right", {
                exec: function(e) {
                    var selection = e.getSelection();
                    var selectedElement = selection.getStartElement();
                    if(selectedElement){
                        insertCol(selectedElement,'right');
                    }
                }
            });
            editor.addCommand("bh_table_delete_col", {
                exec: function(e) {
                    var selection = e.getSelection();
                    var selectedElement = selection.getStartElement();
                    if(selectedElement){
                        deleteCol(selectedElement);
                    }
                }
            });
            editor.addCommand("bh_table_delete", {
                exec: function(e) {
                    var selection = e.getSelection();
                    var selectedElement = selection.getStartElement();
                    if(selectedElement){
                        deleteTable(selectedElement);
                    }
                }
            });
            
            if (editor.contextMenu) {
                editor.addMenuGroup('bh_table_row',2);
                editor.addMenuGroup('bh_table_delete_row');
                editor.addMenuGroup('bh_table_col',2);
                editor.addMenuGroup('bh_table_delete_col');
                editor.addMenuGroup('bh_table_delete');
                editor.addMenuGroup('edit_adv_settings');
                editor.addMenuItems({
                    'bh_table_row' :
                        {
                            label : 'Insert Row',
                            group : 'bh_table_row',
                            order : 1,
                            getItems : function() {
                                return {
                                    bh_table_add_row_before : CKEDITOR.TRISTATE_OFF,
                                    bh_table_add_row_after : CKEDITOR.TRISTATE_OFF
                                };
                            }
                        },
                    'bh_table_add_row_before':{
                        label: "Insert row before",
                        group: "bh_table_row",
                        command: "bh_table_add_row_before",
                        order : 1
                    },
                    'bh_table_add_row_after':{
                        label: "Insert row after",
                        group: "bh_table_row",
                        command: "bh_table_add_row_after",
                        order : 2
                    },
                    'bh_table_delete_row':{
                        label: "Delete Row",
                        group: "bh_table_delete_row",
                        command: "bh_table_delete_row",
                        order : 2
                    },
                    'bh_table_col':{
                        label : 'Insert Column',
                        group : 'bh_table_col',
                        order : 3,
                        getItems : function() {
                            return {
                                bh_table_add_col_left : CKEDITOR.TRISTATE_OFF,
                                bh_table_add_col_right : CKEDITOR.TRISTATE_OFF
                            };
                        }
                    },
                    'bh_table_add_col_left':{
                        label: "Insert column at left",
                        group: "bh_table_col",
                        command: "bh_table_add_col_left",
                        order : 1
                    },
                    'bh_table_add_col_right':{
                        label: "Insert column at right",
                        group: "bh_table_col",
                        command: "bh_table_add_col_right",
                        order : 2
                    },
                    'bh_table_delete_col':{
                        label: "Delete Column",
                        group: "bh_table_delete_col",
                        command: "bh_table_delete_col",
                        order : 4
                    },
                    'bh_table_delete':{
                        label: "Delete Table",
                        group: "bh_table_delete",
                        command: "bh_table_delete",
                        order : 5
                    },
                    'edit_adv_settings':{
                        label: "Edit Table Advanced Settings",
                        group: "edit_adv_settings",
                        command: "bh_table_edit_adv_settings",
                        order : 6
                    },
                });
            
                editor.contextMenu.addListener( function(element) {
                    // menu item state is resolved on commands.
                    var selectedElement = $(element.$);
                    var parent = selectedElement.closest(".bh-cke-bh-table-wrapper");
                    if(parent.length > 0){
                        return {
                            bh_table_row: CKEDITOR.TRISTATE_OFF,
                            bh_table_delete_row: CKEDITOR.TRISTATE_OFF,
                            bh_table_col: CKEDITOR.TRISTATE_OFF,
                            bh_table_delete_col: CKEDITOR.TRISTATE_OFF,
                            bh_table_delete: CKEDITOR.TRISTATE_OFF,
                            edit_adv_settings: CKEDITOR.TRISTATE_OFF
                        };
                    }
                });
            }
        },
        init: function( editor )
        {
            var pluginDirectory = this.path;
            editor.addContentsCss(pluginDirectory + 'styles/bh_table.css');
            var header_bg_class = '';
            editor.addCommand('bh_table', new CKEDITOR.dialogCommand('bh_table', {
				allowedContent: 'div{*}(*)'
            }));
    
            editor.ui.addButton('BhTable', {
				label : 'BH Table',
				toolbar : 'insert',
				command : 'bh_table',
				icon : this.path + 'images/icon.png'
            });
            
            
            CKEDITOR.dialog.add('bh_table', function (instance) {
                var rows_invalid_error = 'Please enter valid number of rows';
                var cols_invalid_error = 'Please enter valid number of cols';
                return {
                    title : 'BH Table Properties',
                    minWidth : 550,
                    minHeight : 120,
                    contents :[
                        {
                            id : 'bh-basic',
                            label: 'Basic Settings',
                            elements :[
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
                                    required: true,
                                    validate : function () {
                                        if (!this.getValue()) {
                                            alert(cols_invalid_error);
                                            return false;
                                        }
                                        else{
                                            cols = parseInt(this.getValue());
            
                                            if (this.getValue().length === 0 ||  isNaN(cols))
                                            {
                                                alert(cols_invalid_error);
                                                return false;
                                            }
                                        }
                                    }
                                },
                            ],
                        },
                        {
                            id : 'bh-advanced',
                            label: 'Advanced Settings',
                            elements :[
                                {
                                    id: "selectHeader",
                                    type: 'select',
                                    label: 'Select Header Property:',
                                    labelLayout: 'horizontal',
                                    items: [ [ 'No Header' ], [ 'First Row' ], [ 'First Column' ], [ 'Both' ] ],
                                    'default': 'First Row',
                                    validate : function () {
                                        if (this.getValue()) {
                                            header = this.getValue();
                                            headerclass = 'bh-cke-bh-table-header--'+header.toLowerCase().replace(' ','-');
                                        }
                                    }
                                },
                                {
                                    id : 'selectBorders',
                                    type : 'select',
                                    label : 'Select Border Property:',
                                    labelLayout: 'horizontal',
                                    items: [ [ 'Grid' ], [ 'Horizontal' ], [ 'Vertical' ]],
                                    'default': 'Grid',
                                    validate : function () {
                                        if (this.getValue()) {
                                            border = this.getValue();
                                            borderclass ='bh-cke-bh-table-border--'+border.toLowerCase();
                                        }
                                    }
                                },
                                {
                                    type: 'checkbox',
                                    id: 'header_bg',
                                    label: 'Header with background color',
                                    labelLayout: 'horizontal',
                                    validate: function() {
                                        if(this.getValue())
                                            header_bg_class = 'bh-cke-bh-table-header-with-bg';
                                        else
                                            header_bg_class = '';
                                    }
                                },
                                {
                                    type: 'html',
                                    html: '<div style="border-top: 1px solid black;margin-left:5px;margin-top: 35px;padding-top: 5px;"><p style="font-size:12px;font-weight:bold">Notes for correct styling :</p><ul><li style="font-size:12px;"><span style="font-weight:bold;font-size:12px;">Product Comparison Table :</span> select <span style="font-weight:bold;font-size:12px;">Header Property - Both</span> and <span style="font-weight:bold;font-size:12px;">Border Property - Grid</span></li><li style="font-size:12px;"><span style="font-weight:bold;font-size:12px;">Corporate Responsibility Details - Engaging with stakeholders Table :</span> select <span style="font-weight:bold;font-size:12px;">Header Property - First Row</span> and <span style="font-weight:bold;font-size:12px;">Border Property - Grid</span></li></ul></div>'
                                },
                            ],
                        }
                    ],
                    onOk: function()
                    {
                        var content = '';
                        if(rows > 0)
                        {
                            content +='<div class="bh-cke-bh-comparision-table-wrapper"><div class="bh-cke-bh-table-wrapper '+borderclass+' '+headerclass+' '+header_bg_class+'">';
                            var i;
                            for(i=1; i <= rows; i++){
                                content += createRowHtml(cols,i,header);
                            }
                            content +='</div></div>';
                        }
                        var element = CKEDITOR.dom.element.createFromHtml(content);
                        var instance = this.getParentEditor();
                        instance.insertElement(element);
                    }
                }
            });

            /* Edit advanced settings dialog box */
            editor.addCommand( 'bh_table_edit_adv_settings',new CKEDITOR.dialogCommand( 'bh_table_edit_adv_settings' ) );
            CKEDITOR.dialog.add('bh_table_edit_adv_settings', function (instance) {
                return {
                    title : 'BH Table Advanced Settings',
                    minWidth : 550,
                    minHeight : 120,
                    contents :[
                        {
                            id : 'bh-advanced-edit',
                            label: 'Advanced Settings',
                            elements :[
                                {
                                    id: "selectEditHeader",
                                    type: 'select',
                                    label: 'Select Header Property:',
                                    labelLayout: 'horizontal',
                                    items: [ [ 'No Header' ], [ 'First Row' ], [ 'First Column' ], [ 'Both' ] ],
                                    setup : function(element)
                                    {
                                        header = getTableAdvancedSetting(element,'header');
                                        this.setValue(header);
                                    },
                                    validate : function () {
                                        if (this.getValue()) {
                                            header = this.getValue();
                                            headerclass = 'bh-cke-bh-table-header--'+header.toLowerCase().replace(' ','-');
                                        }
                                    }
                                },
                                {
                                    id : 'selectEditBorders',
                                    type : 'select',
                                    label : 'Select Border Property:',
                                    labelLayout: 'horizontal',
                                    items: [ [ 'Grid' ], [ 'Horizontal' ], [ 'Vertical' ]],
                                    setup : function(element)
                                    {
                                        border = getTableAdvancedSetting(element,'border');
                                        this.setValue(border);
                                    },
                                    validate : function () {
                                        if (this.getValue()) {
                                            border = this.getValue();
                                            borderclass ='bh-cke-bh-table-border--'+border.toLowerCase();
                                        }
                                    }
                                },
                                {
                                    type: 'checkbox',
                                    id: 'edit_header_bg',
                                    label: 'Header with background color',
                                    labelLayout: 'horizontal',
                                    setup : function(element)
                                    {
                                        var header_bg = getTableAdvancedSetting(element,'header_bg');
                                        this.setValue(header_bg);
                                    },
                                    validate: function() {
                                        if(this.getValue())
                                            header_bg_class = 'bh-cke-bh-table-header-with-bg';
                                        else
                                            header_bg_class = '';
                                    }
                                },
                                {
                                    type: 'html',
                                    html: '<div style="border-top: 1px solid black;margin-left:5px;margin-top: 35px;padding-top: 5px;"><p style="font-size:12px;font-weight:bold">Notes for correct styling :</p><ul><li style="font-size:12px;"><span style="font-weight:bold;font-size:12px;">Product Comparison Table :</span> select <span style="font-weight:bold;font-size:12px;">Header Property - Both</span> and <span style="font-weight:bold;font-size:12px;">Border Property - Grid</span></li><li style="font-size:12px;"><span style="font-weight:bold;font-size:12px;">Corporate Responsibility Details - Engaging with stakeholders Table :</span> select <span style="font-weight:bold;font-size:12px;">Header Property - First Row</span> and <span style="font-weight:bold;font-size:12px;">Border Property - Grid</span></li></ul></div>'
                                },
                            ],
                        }
                    ],
                    // This method is invoked once a dialog window is loaded. 
                    onShow : function()
                    {
                        var sel = editor.getSelection(),
                        element = sel.getStartElement();
                        this.element = element;    
                        // Invoke the setup functions of the element.
                        this.setupContent( this.element );
                    },				
                    onOk: function()
                    {
                        var selectedElement = this.element;
                        var element = $(selectedElement.$);
                        var table_elm = element.closest('.bh-cke-bh-table-wrapper');
                        
                        if(table_elm)
                        {
                            var classArr = ['bh-cke-bh-table-header--no-header', 'bh-cke-bh-table-header--first-row', 'bh-cke-bh-table-header--first-column', 'bh-cke-bh-table-header--both'];
                            var borderArr = [ 'bh-cke-bh-table-border--grid', 'bh-cke-bh-table-border--horizontal', 'bh-cke-bh-table-border--vertical'];
                            var classList = table_elm.attr('class').split(/\s+/);
                            $.each(classList, function(index, item) {
                                
                                if(classArr.indexOf(item) != -1)
                                {
                                    table_elm.removeClass(item);
                                    table_elm.addClass(headerclass);
                                    
                                    table_elm.find('.bh-cke-bh-table-col-wrapper.bh-cke-bh-table-header').removeClass('bh-cke-bh-table-header');
                                    if(header == 'First Row')
                                    {
                                        table_elm.find('.bh-cke-bh-table-row-wrapper:nth-child(1) .bh-cke-bh-table-col-wrapper').addClass('bh-cke-bh-table-header');
                                    }else{
                                        if(header == 'First Column')
                                        {
                                            table_elm.find('.bh-cke-bh-table-row-wrapper .bh-cke-bh-table-col-wrapper:nth-child(1)').addClass('bh-cke-bh-table-header');
                                        }else{
                                            if(header == 'Both')
                                            {
                                                table_elm.find('.bh-cke-bh-table-row-wrapper .bh-cke-bh-table-col-wrapper:nth-child(1)').addClass('bh-cke-bh-table-header');
                                                table_elm.find('.bh-cke-bh-table-row-wrapper:nth-child(1) .bh-cke-bh-table-col-wrapper').removeClass('bh-cke-bh-table-header').addClass('bh-cke-bh-table-header');
                                            }
                                        }
                                    }
                                }

                                if(borderArr.indexOf(item) != -1)
                                {
                                    table_elm.removeClass(item);
                                    table_elm.addClass(borderclass);
                                }
                                if(item == 'bh-cke-bh-table-header-with-bg')
                                {
                                    table_elm.removeClass(item);
                                }
                            });
                            if(header_bg_class.length > 0)
                            {
                                table_elm.addClass(header_bg_class);
                            }
                        }
                    }
                }
            });
        }
    });
    /*create tow html function */
    function createRowHtml(cols,i,header) {
        var rowHtml = '<div class="bh-cke-bh-table-row-wrapper">';
        var j;
        for(j=1; j<=cols; j++){
            var colHtml = '<div class="bh-cke-bh-table-col-wrapper">';
            if((i == 1 && (header == 'First Row' || header == 'Both')) || (j==1 && (header == 'First Column' || header == 'Both')))
            {
                colHtml = '<div class="bh-cke-bh-table-col-wrapper bh-cke-bh-table-header">';
            }
            rowHtml += colHtml;
            rowHtml += '<p class="hidden">&nbsp;</p></div>';
        }
        rowHtml += '</div>';
        return rowHtml;
    }
    /* funtion used to insert new row in existing table*/
    function insertRow(selectedElement,position) {
        header = getTableAdvancedSetting(selectedElement,'header');
        
        var element = $(selectedElement.$);
        var parent_elm;
        if(element.hasClass('bh-cke-bh-table-row-wrapper'))
        {
            parent_elm = element;
        }else{
            parent_elm = element.closest('.bh-cke-bh-table-row-wrapper');
        }
        
        if(parent_elm.length > 0){
            cols = parent_elm.find('.bh-cke-bh-table-col-wrapper').length;
            var rowhtml = createRowHtml(cols,0,header);
            if(position == 'before'){
                parent_elm.before(rowhtml);
            }else{
                parent_elm.after(rowhtml);
            }
            rows++;
        }
    }
     /* funtion used to delete row*/
    function deleteRow(selectedElement) {
        var element = $(selectedElement.$);
        var parent_elm;
        if(element.hasClass('bh-cke-bh-table-row-wrapper'))
        {
            parent_elm = element;
        }else{
            parent_elm = element.closest('.bh-cke-bh-table-row-wrapper');
        }
        if(parent_elm.length > 0){
            parent_elm.remove();
            rows--;
        }
    }
     /* funtion used to insert new column in existing table*/
    function insertCol(selectedElement,position) {
        header = getTableAdvancedSetting(selectedElement,'header');
        var element = $(selectedElement.$);
        var table_elm;
        var parent_elm;
        if(element.hasClass('bh-cke-bh-table-col-wrapper'))
        {
            parent_elm = element;
        }else{
            parent_elm = element.closest('.bh-cke-bh-table-col-wrapper');
        }
        if(parent_elm.length > 0)
        {
            table_elm = parent_elm.closest('.bh-cke-bh-table-wrapper');
            var index = parent_elm.index();
            var colindex = index+1;
            var colhtml = '<div class="bh-cke-bh-table-col-wrapper"><p class="hidden">&nbsp;</p></div>';
               
            if(table_elm.length > 0){
                if(header == 'First Row' || header == 'Both')
                {
                    var firstRowCols = table_elm.find('.bh-cke-bh-table-row-wrapper:nth-child(1) .bh-cke-bh-table-col-wrapper:nth-child('+colindex+')');
                    var headerColsHtml =  '<div class="bh-cke-bh-table-col-wrapper bh-cke-bh-table-header"><p class="hidden">&nbsp;</p></div>';
                    if(firstRowCols.length > 0){
                        if(position == 'left'){
                            firstRowCols.before(headerColsHtml);
                        }else{
                            firstRowCols.after(headerColsHtml);
                        }
                    }
                    var allIndexedCols =  table_elm.find('.bh-cke-bh-table-row-wrapper:gt(0) .bh-cke-bh-table-col-wrapper:nth-child('+colindex+')');
                }else{
                    var allIndexedCols =  table_elm.find('.bh-cke-bh-table-row-wrapper .bh-cke-bh-table-col-wrapper:nth-child('+colindex+')');
                }
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
        if(element.hasClass('bh-cke-bh-table-col-wrapper'))
        {
            parent_elm = element;
        }else{
            parent_elm = element.closest('.bh-cke-bh-table-col-wrapper');
        }
        if(parent_elm.length > 0)
        {
            table_elm = parent_elm.closest('.bh-cke-bh-table-wrapper');
            var index = parent_elm.index();
            if(table_elm.length > 0){
                var colindex = index+1;
                var allIndexedCols =  table_elm.find('.bh-cke-bh-table-row-wrapper .bh-cke-bh-table-col-wrapper:nth-child('+colindex+')');
                if(allIndexedCols.length > 0){
                    allIndexedCols.remove();
                    cols--;
                }
            }
        }
    }
     /* funtion used to delete existing table*/
    function deleteTable(selectedElement) {
        var element = $(selectedElement.$);
        var parent_elm;
        if(element.hasClass('bh-cke-bh-table-wrapper'))
        {
            parent_elm = element;
        }else{
            parent_elm = element.closest('.bh-cke-bh-table-wrapper');
        }
        if(parent_elm.length > 0){
            parent_elm.remove();
        }
    }
    function getTableAdvancedSetting(selectedElement,settingName)
    {
        if (selectedElement)
        {
            var element = $(selectedElement.$);
            var table_elm = element.closest('.bh-cke-bh-table-wrapper');
            var newHeader,newBorder;var newHeaderBg = false;
            if(table_elm)
            {
                var classList = table_elm.attr('class').split(/\s+/);
                
                $.each(classList, function(index, item) {
                    switch(item) {
                        case 'bh-cke-bh-table-header--no-header':
                            newHeader = 'No Header';
                            break;
                        case 'bh-cke-bh-table-header--first-row':
                            newHeader = 'First Row';
                            break;
                        case 'bh-cke-bh-table-header--first-column':
                            newHeader = 'First Column';
                            break;
                        case 'bh-cke-bh-table-header--both':
                            newHeader = 'Both';
                            break;
                        case 'bh-cke-bh-table-border--grid':
                            newBorder = 'Grid';
                            break;
                        case 'bh-cke-bh-table-border--horizontal':
                            newBorder = 'Horizontal';
                            break;
                        case 'bh-cke-bh-table-border--vertical':
                            newBorder = 'Vertical';
                            break;
                        case 'bh-cke-bh-table-header-with-bg':
                            newHeaderBg = true;
                            break;
                      }
                });
                
            }
            if(settingName == 'border')
            return newBorder;
            else
            {
                if(settingName == 'header')
                    return newHeader;
                else
                    return newHeaderBg;
            }
            
        }
    }
})(jQuery);

