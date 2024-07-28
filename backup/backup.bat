@if (@CodeSection == @Batch) @then
@echo off & setlocal

set "URL=http://localhost/upsilon/index.php?r=pos&f=backupNow"
cscript /nologo /e:jscript "%~f0" "%URL%"

goto :EOF
@end // end batch / begin JScript chimera

var x = WSH.CreateObject("Microsoft.XMLHTTP");

x.open("GET",WSH.Arguments(0),true);
x.setRequestHeader('User-Agent','XMLHTTP/1.0');
x.send('');
while (x.readyState != 4) WSH.Sleep(50);