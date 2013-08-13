var gridster, token, choTot, idle= 0,currentCol = 0, storageID = [],to,from;
$(function() {
    choTot = {
        init : function(){
            this._createDragGrid();
            this._update();
            this._idleTracking();
        },
        _update:function(){
            setInterval(function(){
                if(idle > chototSetting.idleInterval){
                    choTot._gridRefresh();
                }
            }, chototSetting.runInterval);
        },
        _gridRefresh: function(){
            choTot._resetIdle();
            this._updateStorageID();
            $.get("chotot/update", { from: from, to: to})
                .done(function(data) {
                    $('.gridster').html(data);
                    choTot._createDragGrid();
                });
            choTot._addNewAds();
        },
        _addNewAds: function(){
            choTot._resetIdle();
            $.get("chotot/update", { "new": true, from: to})
                .done(function(data) {
                    if(data){
                        choTot._updateCurrentCol();
                        gridster.add_widget(data, 1, 1,currentCol,1);
                        choTot._updateStorageID();
                    }
                });
        },
        _updateCurrentCol: function(){
            if(currentCol == chototSetting.max_cols)
                currentCol = 0;
            currentCol++;
        },
        _createDragGrid: function(){
            gridster = $(".gridster  ul").gridster({
                widget_margins: [5, 5],
                widget_base_dimensions: [80, 80],
                max_cols:chototSetting.max_cols,
                min_rows:1,
                serialize_params: function($w, wgd) { return { col: wgd.col, row: wgd.row,id:$w.data('id') } },
                draggable: {stop:function(event, ui){choTot._stopCallback() },start:function(){ choTot._resetIdle(); }}
            }).data('gridster');

            this._updateStorageID();
            this._stopCallback();
        },
        _updateStorageID: function(){
            $('li[data-id]').each(function(a,b) {
                storageID.push($(b).data('id'));
            });
            from = Math.min.apply(Math,storageID);
            to = Math.max.apply(Math,storageID);
        },
        _stopCallback:function() {
            token = $("input[name='_token']").val();
            $.post("chotot/update", { _token:token,ads:gridster.serialize()} );
        },
        _idleTracking: function(){
            setInterval(function(){ idle++ }, chototSetting.runInterval);
            $( document).bind('mousemove keypress mouseover',function() {
                choTot._resetIdle();
            })
        },
        _resetIdle: function(){
            idle = 0;
        }
    };
    choTot.init();
})
