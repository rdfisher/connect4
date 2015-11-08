var App = function() {
    var latestId = 0;
    var instance = this;
    var loop;
    
    this.setLatestId = function(id) {
        latestId = id;
    };
    
    this.getLatestId = function() {
        return latestId;
    };
    
    this.update = function() {
        $.get('/archive?since=' + instance.getLatestId(), function(data) {
            $(data).each(function(i, game) {
                game.board.reverse();
                instance.setLatestId(game.id);
                var divId = 'game_' + game.id
                $('#archive').append('<div id="' + divId + '" class="row game"></div>');
                var ractive = new Ractive({
                    el: '#' + divId,
                    template: '#game',
                    data: game
                });
            });
        });
    };
    
    this.run = function() {
        loop = setInterval(function () {
            instance.update();
        },3000);
    };
    
    this.stop = function() {
       clearInterval(loop); 
    };
};

var app = new App();
app.run();