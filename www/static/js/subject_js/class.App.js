/**
 * Created by yuan on 16/8/15.
 */

function App()
{
    if ( arguments.callee._singletonInstance )
    {
        return arguments.callee._singletonInstance;
    }
    arguments.callee._singletonInstance = this;

    this.dataMap = new Array();
    this.set = function( key, value )
    {
        this.dataMap[key] = value;
    };
    this.get = function( key )
    {
        return this.dataMap[key];
    };
}


