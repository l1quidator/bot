const system = require("system");
const webpage = require("webpage");

var args = system.args;

var page = webpage.create();
page.open(args[1], function(status){
    if (status == "success")
        console.log(page.content);
    else
        console.log("FAIL");

    phantom.exit();
});