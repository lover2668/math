/**
 * Created by linxiao on 17/3/20.
 */
// Make a plugin that alerts when the player plays
videojs.plugin('myPlugin', function(myPluginOptions) {
    myPluginOptions = myPluginOptions || {};

    var player = this;
    var alertText = myPluginOptions.text || 'Player is playing!'

    player.on('play', function(){
        alert(alertText);
    });
});
// USAGE EXAMPLES
// EXAMPLE 1: New player with plugin options, call plugin immediately
var player1 = videojs('idOne', {
    myPlugin: {
        text: 'Custom text!'
    }
});