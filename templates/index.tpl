{include file="header.tpl" title=foo}
The current date and time is {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}
    Hello, {$name}!
{include file="footer.tpl"}