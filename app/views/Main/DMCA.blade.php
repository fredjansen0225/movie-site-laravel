@extends('Main.Boilerplate')

@section('bodytag')
	<body id="privacy">
@stop

@section('content')

 <div class="container" id="content">
 	<h3>Link Removal Policy (DMCA)</h3>

	<p>Film5 take matters of Intellectual Property very seriously and are committed to meeting the needs of content owners. While it should be noted that Film5 is a simple search engine of videos available at a wide variety of third party websites.

<p>Any videos shown on third party websites are the responsibility of those sites and not Film5. We have no knowledge of whether content shown on third party websites is or is not authorized by the content owner as that is a matter between the host site and the content owner. Film5 does not host any content on its servers or network.

<p>Content Owners can use the DMCA protocol to request removal of links to videos that they believe infringe their copyright.

<p>Contact address, <?php echo HTML::mailto('DMCA@film5.co.uk') ?>, Content owners must understand that by having a link removed from Film5 they will not be removing the actual source video from the 3rd party site, users will still be able to view the content online if they go directly to the 3rd party site. Content owners must contact the video hosting site themselves to request removal.</p>

<p>Content owners must also understand that Film5 is a complex system of automatic indexers and robotic scripts; we rarely add links to the website manually, if a video exists on the web we will at some point find it and index it automatically. It is entirely possible that we will index a previously removed video that is hosted using a different URL or different hosting website. If this happens please let us know as per DMCA protocol and we will address the issue. </p>

<p>Please do not send DMCA requests asking for entire categories or shows to be removed. We only accept requests for an actual link to be removed.

<p>If you wish to request multiple link removals please use just one DMCA notice but list all the URL’s in that request.

<p>Thanking you for the patience and cooperation. Send the written infringement notice as an email to <?php echo HTML::mailto('DMCA@film5.co.uk') ?>. Please allow 1-2 business days for an email response. Note that emailing your complaint to other parties such as our Internet Service Provider will not expedite your request and may result in a delayed response due the complaint not properly being filed.


 </div>

@stop


