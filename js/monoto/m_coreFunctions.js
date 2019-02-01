// -----------------------------------------------------------------------------
// custom console.log function
// -----------------------------------------------------------------------------
function logToConsole(source, msg)
{
    // both values empty
    if ((source == "") && (msg == ""))
    {
        console.log("WARNING: got empty SOURCE and MSG in logToConsole");
        return;
    }

    // empty source
    if(source == "")
    {
        console.log(msg);
        return;
    }

    // empty msg
    if(msg == "")
    {
        console.log("Function: "+ source);
        return;
    }

    // default case
    console.log("Function: " + source + " ### "+msg);
}
