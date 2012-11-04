#coding: utf-8
require 'pp'
ga_code = <<FFF

<!-- Customer Engine --> 
<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://ce.chuwa.us:1234/" : "http://ce.chuwa.us:1234/");
document.write(unescape("%3Cscript src='" + pkBaseURL + "ce.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
  var piwikTracker = Piwik.getTracker(pkBaseURL + "ce.php", 1);
  piwikTracker.trackPageView();
  piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src="http://ce.chuwa.us:1234/ce.php?idsite=1" style="border:0" alt="" /></p></noscript>
<!-- End Customer Engine Code -->

FFF

files = Dir.glob("**/*.html")
files.each do |file|
  pp file
  open(file, 'a') { |f| f.puts ga_code }
end
