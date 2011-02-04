<?php /* -*- mode: php -*- */
/* Common content for presenting news as an RSS feed and HTML pages. */

require_once 'vrmlengine_functions.php';

/* For given date, convert it to a Unix timestamp
   (seconds since 1970-01-01 00:00:00 UTC).

   Date is converted with time set to 12:00 UTC this day.
   This way the timestamp should represent the same day,
   when represented as date/time in all timezones.
   Otherwise people around the world could see actually a different
   day in their RSS readers than the day written on WWW page like
   [http://vrmlengine.sourceforge.net/news.php].

   I don't want to write everywhere that my dates are in UTC,
   as since I only want to set dates --- I can make them the same regardless
   of timezone. */
function date_timestamp($year, $month, $day)
{
  return gmmktime(12, 0, 0, $month, $day, $year);
}

/* For RSS feed, URLs must be absolute (some RSS readers,
   like Google RSS on main page, don't handle relative URLs as they
   should. And indeed, no standard guarantees that relative URLs
   in RSS would be handled OK).

   Because of this, use news_a_href_page and news_a_href_page_hashlink
   for news. */
function news_a_href_page($title, $page_name)
{
  return '<a href="' . CURRENT_URL . $page_name . '.php">' . $title . '</a>';
}

function news_a_href_page_hashlink($title, $page_name, $anchor)
{
  return '<a href="' . CURRENT_URL . $page_name . '.php#' .
    $anchor . '">' . $title . '</a>';
}


  /* This an array of news entries.
     It is in format accepted by rss_generator class, but also has some
     extras for news_to_html:

     - year, month, day fields (shown at some places,
       and pubDate timestamp will be auto-generated based on them)

     - short_description: teaser description on the main page.
       If empty, we will take teaser from the normal 'description',
       up to the magic delimiter <!-- teaser ... -->.

       "..." inside this comment will be the additional text present
       only in the teaser. You should use this to close HTML elements
       that should close now in teaser, but remain open in full version.

       If this delimiter is not present, then teaser is just equal to
       full description.

     - guid will be used also for NEWS-ID, to have news.php?item=NEWS-ID page.
       guid is optional --- we'll generate default guid based on date and title,
       if not set.

     - link: do not give it here.
       We'll set link to the URL like xxx/news.php?id=xxx.

     They must be ordered from the newest to the oldest.
     While it doesn't matter for RSS (feed will be sorted anyway by news
     reader), my HTML converting code depends on it (first feed is the "latest
     update", and feeds are presented in given order on news page).
  */

  $news = array(

/* --------------------------------------------------------------------------- */

    array('title' => 'view3dscene 3.9.0: new renderer, GLSL attributes, multiple viewports. Also: &quot;fundry&quot;, a way to donate to particular feature',
          'year' => 2011,
          'month' => 2,
          'day' => 6,
          'short_description' => '',
          'description' =>
vrmlengine_thumbs(array(
  array('filename' => 'glsl_flutter.png', 'titlealt' => 'GLSL demo &quot;flutter&quot; (from FreeWRL examples)'),
  array('filename' => 'venus_spheremap.png', 'titlealt' => 'Venus model with environment sphere mapping (model referenced from FreeWRL examples)'),
), 1) .
'<p>We\'re proud to release a new version of <a href="http://vrmlengine.sourceforge.net/view3dscene.php">view3dscene 3.9.0</a>, our VRML/X3D (and other 3D models) browser. As usual, the new release is accompanied by new <a href="http://vrmlengine.sourceforge.net/kambi_vrml_game_engine.php">Kambi VRML game engine 2.4.0</a> (where all the magic actually happens) and new <a href="http://vrmlengine.sourceforge.net/kambi_vrml_test_suite.php">Kambi VRML test suite 2.11.0</a> releases.</p>

<ol>
  <li><p>The main new feature of this release is a <b>new modern renderer</b>. It opens the door for pure shader rendering in the next release, which hopefully will blow your mind :) Features already implemented while improving the renderer:</p>

    <ul>
      <li>GLSL attributes from VRML/X3D nodes: support for <a href="http://www.web3d.org/x3d/specifications/ISO-IEC-19775-1.2-X3D-AbstractSpecification/Part01/components/shaders.html"><tt>FloatVertexAttribute</tt>, <tt>Matrix3VertexAttribute</tt>, <tt>Matrix4VertexAttribute</tt> nodes</a>.</li>
      <li><a href="http://web3d.org/x3d/specifications/ISO-IEC-19775-1.2-X3D-AbstractSpecification/Part01/components/enveffects.html#LocalFog">LocalFog</a> support. This allows you to limit (or turn off) fog for particular shapes. Our <a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_fog_volumetric">volumetric fog</a> extensions are available for this type of fog, as well as normal <tt>Fog</tt>.</li>
      <li><a href="http://web3d.org/x3d/specifications/ISO-IEC-19775-1.2-X3D-AbstractSpecification/Part01/components/enveffects.html#FogCoordinate">FogCoordinate</a> node support (explicit per-vertex fog intensities). <a href="http://vrmlengine.sourceforge.net/vrml_implementation_environmentaleffects.php">Support details are here</a>.</li>
      <li><a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_bump_mapping">Bump mapping extensions</a> support for every shape (including X3D triangle/quad sets/strips/fans).</li>
      <li><tt>ElevationGrid.creaseAngle</tt> is now working correctly (previously only all smooth or all flat normals were possible for ElevationGrid).</li>
      <li>Loading GLSL shader source from data URI. For example, you can prefix inline shader source with line "<tt>data:text/plain,</tt>", which is a spec-conforming method of putting shader source inline (even though you can still omit it for our engine). See <a href="http://vrmlengine.sourceforge.net/vrml_implementation_shaders.php">examples in our docs here</a>, also the "GLSL Vertex Shader" example in <a href="http://freewrl.sourceforge.net/examples.html">FreeWRL examples</a>.</li>
    </ul>

    <p>With the new renderer, you should enjoy better speed on many scenes &mdash; in some cases the improvement is large (although, admittedly, in some cases it\'s not really noticeable). If you\'re curious, some (not impressive, but also not bad) <a href="https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/branches/view3dscene-old-renderer-for-comparison/STATS.txt">results are here</a>.</p>

    <p>For programmers, a description of how the new renderer works is available in our <a href="http://vrmlengine.sourceforge.net/vrml_engine_doc/output/xsl/html/chapter.opengl_rendering.html">documentation (chapter "Geometry Arrays")</a>. You can grab it in PDF or other formats from <a href="http://vrmlengine.sourceforge.net/vrml_engine_doc.php">here</a>.</p></li>

  <li><p>Another new feature are <b>multiple viewports</b>. Just open any scene, and try the new <i>Display -&gt; 1/2/4 viewports</i> menu items, and you will see what I mean. Hope you like this :) Remember that the main (upper-left) viewport is still the central one, for example it controls the headlight.</p>

    <p>Thanks to Jens van Schelve for suggesting this. A cool fact: the guys at <a href="http://www.ssbwindsystems.de/">SSB Wind Systems</a> are using our <a href="http://vrmlengine.sourceforge.net/view3dscene.php">view3dscene</a> to visualise wind turbine simulations :) You can see a screenshot of their simulation output on the right.</p></li>

  <li><p>A new website feature is the possibility to <b>donate money specifically for implementing a particular feature</b>: <a href="https://fundry.com/project/91-kambi-vrml-game-engine">go to fundry page for our engnine</a>. The fundry widget is available also on the <a href="http://vrmlengine.sourceforge.net/support.php">Forum page (that I overuse for other support and donation links)</a>.</p></li>

  <li><p>At the end: I decided to <b>deprecate some of our old extensions</b>. As far as I know noone used them, and they are rather useless in the light of new features:</p>

    <ul>
      <li>Fog.alternative &mdash; useless, because all decent (even old) GPUs support EXT_fog_coord. And for ancient GPUs automatic fallback to the non-volumetric fog works well enough.
      <li>Material.fogImmune &mdash; useless, as newly implemented LocalFog node allows you to locally disable fog too. LocalFog also allows much more (like locally <i>enable</i> fog), and it\'s part of the X3D specification. So our poor Material.fogImmune extension has no place anymore.
      <li>Also, menu item to switch "Smooth Shading" is removed from view3dscene menu. Forcing flat shading on the whole scene seemed rather useless debug feature. You can always set IndexedFaceSet.creaseAngle = 0 in your files (in fact it\'s the default) to achieve the same effect.
    </ul>
  </li>
</ol>'),

    array('title' => 'Development news: first milestone of new renderer reached, GLSL attributes and other new features',
          'year' => 2011,
          'month' => 1,
          'day' => 18,
          'short_description' => '',
          'description' =>
vrmlengine_thumbs(array(
  array('filename' => 'glsl_flutter.png', 'titlealt' => 'GLSL demo &quot;flutter&quot; (from FreeWRL examples)'),
  array('filename' => 'venus_spheremap.png', 'titlealt' => 'Venus model with environment sphere mapping (model referenced from FreeWRL examples)'),
), 1) .
'<p>I have committed to SVN a large rework of our renderer. Everything is now rendered through <i>locked interleaved vertex arrays</i>. And I mean <i>everything</i>, really every feature of VRML/X3D shapes &mdash; all colors, normals, texture coords etc. are loaded through vertex arrays. This opens wide the door for much more optimized, modern renderer using exclusively VBOs for nearest release. It will also eventually allow OpenGL ES version for modern mobile phones. (But shhhhh, this is all not ready yet.)</p>

<p>Improvements already done while improving our renderer:</p>

<ul>
  <li>GLSL attributes from VRML/X3D nodes: support for <tt>FloatVertexAttribute</tt>, <tt>Matrix3VertexAttribute</tt>, <tt>Matrix4VertexAttribute</tt> nodes.</li>
  <li><a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_bump_mapping">Bump mapping extensions</a> support for every shape (including X3D triangle/quad sets/strips/fans).</li>
  <li><tt>ElevationGrid.creaseAngle</tt> is now working correctly (previously only all smooth or all flat normals were possible for ElevationGrid).</li>
  <li><tt>FogCoordinate</tt> node support (explicit per-vertex fog intensities).</li>
  <li>Loading GLSL shader source from data URI. For example, you can prefix inline shader source with line "<tt>data:text/plain,</tt>", which is a spec-conforming method of putting shader source inline (even though you can still omit it for our engine). For a demo, see the "GLSL Vertex Shader" example in <a href="http://freewrl.sourceforge.net/examples.html">FreeWRL examples</a>.</li>
</ul>

<p>As always, you can test the latest development version by downloading binary from our <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/">nightly builds</a>.</p>

<p>(Note that the <tt>Text</tt> nodes are an exception, they don\'t benefit from new renderer features. Parts of <tt>Text</tt> geometry are rendered through a different method, that is not integrated with vertex arrays. This will not be touched for next release, text nodes are not that much important.)</p>

<p>(Note that the fglrx (ATI Radeon proprietary drivers under Linux) sucks, as always. GLSL vertex attributes, and bump mapping, currently require that you change <i>Preferences -&gt; Rendering Optimizaton</i> to None. That\'s because glEnableVertexArrayARB seemingly doesn\'t work inside display lists. This problem doesn\'t occur with any other drivers (even with Radeon drivers for the same graphic card but on Mac OS X), so it\'s another clear fglrx fault. This will be fixed nicer before release, as VBO renderer without display lists will probably avoid these problems entirely.)</p>'),

    array('title' => 'view3dscene 3.8.0: 3D sound, skinned H-Anim, more',
          'year' => 2011,
          'month' => 1,
          'day' => 6,
          'short_description' => '',
          'description' =>
vrmlengine_thumbs(array(
  array('filename' => 'sound.png', 'titlealt' => 'Sound demo (from Kambi VRML test suite)'),
  array('filename' => 'lucy_test.png', 'titlealt' => 'Lucy (from Seamless3d test page)'),
  array('filename' => 'lucy_joints_visualization.png', 'titlealt' => 'Lucy with our joints visualization'),
), 1) .
'<p><b>3D sound in VRML/X3D</b> worlds is implemented. Grab the new ' . news_a_href_page('view3dscene 3.8.0', 'view3dscene') . ', and for some demo open files <tt>x3d/sound_final.x3dv</tt> and <tt>x3d/sound_location_animate.x3dv</tt> from the ' . news_a_href_page('kambi_vrml_test_suite', 'kambi_vrml_test_suite') . '. ' . news_a_href_page('Detailed documentation for Sound support is here', 'vrml_implementation_sound') . '.</p>

<p>Note that you have to install some additional libraries to hear sounds (OpenAL to hear anything, and VorbisFile to load OggVorbis format). For Windows, these are already included in the zip file, and you actually don\'t have to do anything. For Linux, you should install them using your package managar. For Mac OS X, ' . news_a_href_page('OpenAL is already preinstalled and you can get VorbisFile from fink', 'macosx_requirements') . '.</p>

<p>If you want to mute / unmute sound, you can use <i>File -&gt; Preferences -&gt; Sound</i> menu item of view3dscene. There\'s also <i>File -&gt; Preferences -&gt; Sound Device</i> choice. ' . news_a_href_page('Command-line options for controlling sound are documented here', 'openal') . '.</p>

<p>For developers, as usual we release a <b>new <a href="http://vrmlengine.sourceforge.net/kambi_vrml_game_engine.php">Kambi VRML game engine 2.3.0</a></b>. Besides sound in VRML/X3D, you will notice a new shiny SoundEngine (instance of TALSoundEngine, in ALSoundEngine unit) that makes using OpenAL a breeze from ObjectPascal code. Sample usage:</p>

<pre class="sourcecode">
var Buffer: TALBuffer;
...
Buffer := SoundEngine.LoadBuffer(\'sample.wav\');
SoundEngine.PlaySound(Buffer, ...); // see TALSoundEngine.PlaySound parameters
</pre>

<p>See the <a href="http://vrmlengine.sourceforge.net/reference.php">engine reference</a>, in particular <a href="http://vrmlengine.sourceforge.net/apidoc/html/ALSoundEngine.TALSoundEngine.html">TALSoundEngine class reference</a>, for details. You can try adding this code spinnet to any example in engine sources, e.g. to the <tt>examples/vrml/scene_manager_demos.lpr</tt>.</p>

<p><b>Animating skinned H-Anim humanoids</b> is also implemented. You can use view3dscene to open e.g. <a href="http://www.seamless3d.com/browser_test/index.html">"Lucy" examples</a> from Seamless3D, also "The famous boxman" linked from the bottom of <a href="http://doc.instantreality.org/tutorial/humanoid-animation/">InstantReality H-Anim overview</a>. The <a href="http://vrmlengine.sourceforge.net/vrml_implementation_hanim.php">details about H-Anim support are here</a>. The new view3dscene menu item <i>"Edit -&gt; Add Humanoids Joints Visualization"</i> may be useful too.</p>

<p>See also the video below. At first you see InstantReality results and then the view3dscene. Thanks to Peter "griff" Griffith for testing and creating this video!</p>

' . (!HTML_VALIDATION ? '<object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/v20CFbKWAYU?fs=1&amp;hl=pl_PL"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/v20CFbKWAYU?fs=1&amp;hl=pl_PL" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object>' : '') . '

<p>Some <b>other notable features</b> implemented:</p>

<ul>
  <li><tt>MultiGeneratedTextureCoordinate</tt> node introduced, to better define the <a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_tex_coord">Box/Cone/Cylinder/Sphere.texCoord</a>.</li>
  <li><a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_tex_coord_bounds">Texture coord generation dependent on bounding box (TextureCoordinateGenerator.mode = BOUNDS*)</a>. This allowed fixing shadow maps implementation for the case when shape has a texture but no explicit texture coordinate node.</li>
  <li><a href="http://vrmlengine.sourceforge.net/reference.php">Engine reference for developers</a> improved a lot.</li>
</ul>

<p>Also <b>' . news_a_href_page('castle 0.9.0', 'castle') . ' is released</b>. This doesn\'t bring any new user-visible features, however internally a lot of stuff was simplified and ported to our engine 2.x line.</p>'),

    array('title' => 'Development news: animating skinned H-Anim humanoids',
          'year' => 2010,
          'month' => 12,
          'day' => 22,
          'short_description' => '',
          'description' =>
vrmlengine_thumbs(array(
  array('filename' => 'lucy_test.png', 'titlealt' => 'Lucy (from Seamless3d test page)'),
  array('filename' => 'lucy_joints_visualization.png', 'titlealt' => 'Lucy with our joints visualization'),
  array('filename' => 'hanim_0.png', 'titlealt' => 'BoxMan with joints visualized'),
  array('filename' => 'billboards_with_shadow.png', 'titlealt' => 'Billboards casting shadows'),
), 1) .
'<p>I just implemented animating skinned humanoids, following the H-Anim specification. This is implemented in our engine, and in particular can be used by our ' . news_a_href_page('view3dscene', 'view3dscene') . '.</p>

<p><a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/vrml_implementation_hanim.html">Documentation of current (SVN) H-Anim support is here</a> (when the view3dscene with this will be officially released, it will be <a href="http://vrmlengine.sourceforge.net/vrml_implementation_hanim.php">moved to stable H-Anim support docs</a>).</p>

<p>As usual, you can test the latest development version by downloading binary from our <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/">nightly builds</a>. Sample models are <a href="http://www.seamless3d.com/browser_test/index.html">"Lucy" examples</a> from Seamless3D, also "The famous boxman" linked from the bottom of <a href="http://doc.instantreality.org/tutorial/humanoid-animation/">InstantReality H-Anim overview</a>.</p>

<p>Check out also the new view3dscene menu item <i>"Edit -&gt; Add Humanoids Joints Visualization"</i>.</p>

<p>Other improvements in our engine and ' . news_a_href_page('view3dscene', 'view3dscene') . ':</p>

<ul>
  <li><tt>MultiGeneratedTextureCoordinate</tt> node introduced, to better define the <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/kambi_vrml_extensions.html#section_ext_tex_coord">Box/Cone/Cylinder/Sphere.texCoord (SVN docs)</a>.</li>
  <li><a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/kambi_vrml_extensions.html#section_ext_tex_coord_bounds">Texture coord generation dependent on bounding box (TextureCoordinateGenerator.mode = BOUNDS*) (SVN docs)</a>. This allowed fixing shadow maps implementation for the case when shape has a texture but no explicit texture coordinate node.</li>
  <li>Fix Collada-&gt;VRML conversion (thanks to Simon from <a href="http://apps.sourceforge.net/phpbb/vrmlengine/viewforum.php?f=3">forum</a>).</li>
  <li>Zoom improved, to prevent going too far away from object by zoom-in.</li>
  <li>Help wanted: if you\'re familiar with Mac OS X (and FreePascal), I outlined <a href="http://vrmlengine.sourceforge.net/macosx_requirements.php">here how you can help</a>.</li>
  <li>Fixed <tt>view3dscene --screenshot</tt> modal box on invalid filename.</li>
  <li>When local geometry (like coordinates) is often changed, shape changes into "dynamic" state: it\'s octree will be very simple, so we will not waste time on rebuilding it.</li>
  <li>Make a nice warning when more than one value specified for SFNode field value in X3D encoding.</li>

  <li>Engine terrain demo works also on GPUs without GLSL support.</li>
  <li>Fixes for rendering Walk/Fly tooltips in view3dscene on some GPUs.</li>
  <li>Engine documentation improved a lot, I talked about this in details in <a href="http://vrmlengine.sourceforge.net/news.php?item=2010-12-2-development_news__major_improvements_to_engine_api_reference__future_plans">previous news post</a>.</li>
  <!--li>Memory leaks when reading invalid XML files fixed.</li-->

</ul>
'),

    array('title' => 'Development news: major improvements to engine API reference, future plans',
          'year' => 2010,
          'month' => 12,
          'day' => 2,
          'short_description' => '',
          'description' =>
'<p>In the last few days, I was working hard on making our
<i>engine API reference</i> perfect.
<a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/">The new and improved reference is here</a>, while
' . news_a_href_page('the stable (old) reference is here', 'reference') . '.
Of course at the next engine release, the new one will replace the old one :)</p>

<ul>
  <li><p><b>All units have now a nice documentation</b>, in English, suitable for <a href="http://pasdoc.sipsolutions.net/">PasDoc</a>, with nice formatting, abstracts etc.</p></li>

  <li><p>While doing the above, I also had to revisit some of the really ancient code of the engine (as this is where most of the bad docs, that needed fixing / translating, were). This caused me to <b>cleanup and even remove some of the old cruft from the engine</b> &mdash; total of 7 units are gone (some removed completely, some were trimmed down to a tiny utilities that were integrated into another units), some miscellanaus old hacks (like opengltypes.inc stuff, or TSkyCube, some FPC 1.0.x hacks etc.) are also removed.</p>

    <p>Out of curiosity, I did a line count on *.pas and *inc files in our engine (omitting auto-generated code). The total number is that since 2.2.0, the engine has 3863 lines <b>less</b>, making for code that much cleaner! Yeah!</p></li>

  <li><p><b>Future plans</b>:
    <ul>
      <li><p><i>' . news_a_href_page('castle', 'castle') . ' 1.0.0 release</i> is planned very soon. This will not include any new user-visible new features, but will incorporate all the engine bugfixes and speed improvements from last engine versions. The idea is to signal that "castle" is mostly finished now, and we\'re ready for new challenges :) A new large game using our engine is planned (since quite some time already :)</p></li>

      <li><p><i>' . news_a_href_page('view3dscene', 'view3dscene') . ' 3.8.0</i> is planned to include <a href="http://www.web3d.org/x3d/specifications/ISO-IEC-19775-1.2-X3D-AbstractSpecification/Part01/components/sound.html">X3D Sound component</a> implementation. At least the basic stuff, that works easily using our current OpenAL framework and allocator.</p></li>

      <li><p><i>' . news_a_href_page('view3dscene', 'view3dscene') . ' 3.9.0</i> is planned to have a more modern renderer, where <i>everything</i> rendered in 3D goes through VBO. This should make a performance boost for newer GPUs, also making some dynamic scenes work much faster. For older GPUs, the old rendering method (using locked vertex arrays and display lists) will be kept (and hopefully auto-selected).</p>

        <p>This will also bring many GLSL shaders improvements. Full GLSL-only pipeline should be done in view3dscene 3.9.0 or 3.10.0.</p></li>
    </ul>
  </li>
</ul>'),

    array('title' => 'view3dscene 3.7.0 release: Screen effects, drag sensors, ClipPlane, Billboard, toolbar and much more',
          'year' => 2010,
          'month' => 11,
          'day' => 18,
          'short_description' => '',
          'description' =>
vrmlengine_thumbs(array(
  array('filename' => 'screen_effect_blood_in_the_eyes_1.png', 'titlealt' => 'Screen effect &quot;blood in the eyes&quot;: modulate with reddish watery texture'),
  array('filename' => 'screen_effect_trees.png', 'titlealt' => 'Another screen effect example'),
  array('filename' => 'screen_effects_demo3.png', 'titlealt' => 'Demo of three ScreenEffects defined in VRML/X3D, see screen_effects.x3dv'),
  array('filename' => 'screen_effect_headlight_and_gamma.png', 'titlealt' => 'Screen effect: headlight, gamma brightness (on DOOM E1M1 level remade for our Castle)'),
  array('filename' => 'screen_effect_film_grain.png', 'titlealt' => 'Film grain effect'),
  array('filename' => 'screen_effect_grayscale_negative.png', 'titlealt' => 'Screen effect: grayscale, negative (on Tremulous ATCS level)'),
  array('filename' => 'tooltip_examine.png', 'titlealt' => 'Examine navigation tooltip'),
  array('filename' => 'billboards_0.png', 'titlealt' => 'Billboard demo'),
  array('filename' => 'bridge_final_0.png', 'titlealt' => 'Bridge model in engine examples'),
), 2) .
'<p>After 3 months of work, I\'m proud to present a new release
of our VRML/X3D browser:
' . news_a_href_page('view3dscene 3.7.0', 'view3dscene') . '.</p>

<!--p>There are many new features and improvements, some of which were already
announced in more details on our ' . news_a_href_page('news', 'news') . '.</p-->

<ul>
  <li><p><b>Screen effects</b> is a new eye-candy feature in our engine. Try the <i>View -&gt; Screen Effects</i> menu in ' . news_a_href_page('view3dscene', 'view3dscene') . ' for various effects that can be applied on any 3D scene.</p>

    <p>For people who know a little <a href="http://www.opengl.org/documentation/glsl/">GLSL (OpenGL Shading Language)</a>, this is quite powerful toy for designing your own screen effects. You can define a simple GLSL shader in VRML/X3D file, that processes the screen in any way you like, given the color and depth buffer. <a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions_screen_effects.php">Documentation and examples of defining your own screen effects are here.</a></p>

    <p><small>Developers: Screen effects may also be defined (and controlled) directly from the Object Pascal source code. You only have to override <a href="http://vrmlengine.sourceforge.net/apidoc/html/KambiSceneManager.TKamAbstractViewport.html#ScreenEffects">TKamAbstractViewport.GetScreenEffects and TKamAbstractViewport.ScreenEffectsCount</a> and return your own effects there. See multiple_viewports example source code (' . news_a_href_page('in engine sources in examples/vrml/', 'kambi_vrml_game_engine') . ') for a simple example. And see <a href="http://vrmlengine.svn.sourceforge.net/viewvc/vrmlengine/trunk/view3dscene/v3dscenescreeneffects.pas">v3dscenescreeneffects.pas</a> for more involved example straight from the ' . news_a_href_page('view3dscene', 'view3dscene') . ' sources.</small></p>

    <!--p>Also, the old <i>Change Scene Colors</i> (color modulators inside engine sources) are removed. This was a poor idea, with ugly implementation and little use. New Screen Effects allow much more effects, with a modern implementation.</p-->
  </li>

  <li><p><b>New nodes</b> implemented: <a href="http://vrmlengine.sourceforge.net/vrml_implementation_pointingdevicesensor.php">drag sensors (<tt>PlaneSensor, SphereSensor, CylinderSensor</tt>)</a>,
    <a href="http://vrmlengine.sourceforge.net/vrml_implementation_rendering.php"><tt>ClipPlane</tt>, <tt>ColorRGBA</tt></a>,
    <a href="http://vrmlengine.sourceforge.net/vrml_implementation_navigation.php"><tt>Billboard</tt>, <tt>ViewpointGroup</tt></a>,
    <a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_toggler">Toggler</a>.</p>
  </li>

  <li><p>Major <b>improvements and fixes to existing nodes</b>:
    <tt>Transform</tt> (and similar nodes from H-Anim) animation is greatly optimized.
    Also changing <tt>Transform</tt> node containing light sources works fast now.
    Many <a href="http://vrmlengine.sourceforge.net/vrml_implementation_time.php"><tt>TimeSensor</tt></a>,
    <a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_rendered_texture">RenderedTexture</a>,
    <tt>OrthoViewpoint</tt> improvements.
    <!--Events mechanism is optimized for many fields.-->
    See ' . news_a_href_page('news archive for details', 'news') . '.</p></li>

  <li><p><b>Camera improvements</b>: Examine camera now honors <tt>Viewpoint</tt>
    nodes. Switching navigation mode preserves camera view.
    Smooth transitions (following <tt>NavigationInfo.transitionType, NavigationInfo.transitionTime</tt> fields)
    are done. <a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_head_bobbing">headBobbingDistance
    is renamed into much more suitable headBobbingTime and expressed in seconds</a>.
    Mouse wheel is supported for zooming in Examine mode.</p>
  </li>

  <li><p><b>User interface improvements</b>: nice toolbar at the top of the window,
    with most important buttons. Navigation mode buttons have tooltips (hover
    mouse over them to see) describing camera controls.
    Nice "%s warnings" button.</p></li>

  <li><p>Primitives (<tt>Box, Cone, Cylinder, Sphere</tt>) have the
    <a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_tex_coord">texCoord</a>
    field and work with <a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions_shadow_maps.php">shadow maps</a>,
    multi-texturing, <a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_bump_mapping">bump mapping</a> etc.</p>
  </li>

  <li><p>New functions for <a href="http://vrmlengine.sourceforge.net/kambi_script.php#section_functions_rotation"><b>KambiScript to handle rotations</b></a>.</p></li>
</ul>

<p>Our website and documentation also got a lot of improvements.
Most of them were already announced in previous news items, and will not
be repeated now. Some new improvements:</p>

<ul>
  <li><p>' . news_a_href_page('view3dscene', 'view3dscene') . ' download links,
    and easy instructions to get GNOME integration and thumbnailer,
    are now more visible on view3dscene webpage.</p></li>

  <li><p>The pages describing our ' . news_a_href_page('VRML/X3D implementation status', 'vrml_implementation_status') . ' for each X3D component are much improved. Each component page starts with a very short introduction, describing what the component is and how it\'s used in the most typical cases. Also, the node names are links to actual X3D specification pages.</p>

    <p>The idea behind these improvements is to give interested developers (in particular, the ones not familiar with VRML/X3D yet) a way to orient themselves in the large number of VRML/X3D nodes. We give an easy overview of the component and the links to X3D specification to learn more details.</p>

    <p>And everything is of course interspersed with the details about our engine implementation, it\'s strength and current limitations.</p>
  </li>

  <li><p>Finally, I added ' . news_a_href_page('a section about donating at the bottom of the "Support" page', 'support') . ' and a button to <a href="https://flattr.com/thing/82694/Kambi-VRML-game-engine">donate through Flattr</a> to a couple pages.</p></li>
</ul>

<p>As usual, view3dscene release is accompanied by ' . news_a_href_page('new engine release (2.2.0)', 'kambi_vrml_game_engine') . ' (this is where the magic actually happens :), and ' . news_a_href_page('new Kambi VRML test suite release (2.9.0)', 'kambi_vrml_test_suite') . ' (which contains tests and demos of all the new features).</p>
'),

    array('title' => 'Development news: Billboards, transform optimizations, UI: toolbars and hints, more',
          'year' => 2010,
          'month' => 11,
          'day' => 10,
          'short_description' => '',
          'description' =>
vrmlengine_thumbs(array(
  array('filename' => 'tooltip_examine.png', 'titlealt' => 'Examine navigation tooltip'),
  array('filename' => 'tooltip_walkfly.png', 'titlealt' => 'Walk/Fly navigation tooltip'),
  array('filename' => 'billboards_0.png', 'titlealt' => 'Billboard demo'),
  array('filename' => 'bridge_final_0.png', 'titlealt' => 'Bridge model in engine examples'),
  array('filename' => 'bridge_final_1.png', 'titlealt' => 'Bridge model in engine examples, another view'),
)) .
'<ul>
  <li><p>A major <b>Transform optimization</b> is implemented. This makes Transform animation really working at instant speeds.</p></li>
  <li><p><b>Billboard node</b> is implemented. Useful for sprites and such.</p></li>
  <li><p>At the top of view3dscene window you will now see a nice <b>toolbar</b>. This provides the most important buttons &mdash; open, change navigation mode, change collisions, and view warnings (if any).</p>

    <p>We also clearly visualize now separate "Walk" and "Fly" navigation methods (and "None" in the menu).</p>

    <!--
    The way we allow navigation mode changing is an improvement in itself. Previously we had only Examine, and Walk modes explicitly shown. The "Fly" was not explicitly shown, as it was just "Walk mode with Gravity off (and some prefer... settings different)". Now we have a separate button for "Fly" mode. You can also just change explicitly "Gravity", this will toggle you between "Walk" and "Fly". We also have a separate "None" navigation type.</p

    <p>So the options to change navigation mode should be now cleaner, both in the menu and on the toolbar.</p-->

    <p>Status text (at the bottom) is also shorter now.</p></li>

  <li><p><b>Tooltips ("hints")</b> are implemented for our OpenGL controls.</p>

    <p>They are used by view3dscene to display nice <b>description of key/mouse controls for given navigation mode</b> &mdash; just mouse over the "Examine", "Walk", "Fly" buttons. I really hope that this is useful (for both new and advanced users), comments about how you like it are most welcome. Hopefully, this will make the navigation controls more obvious.</p>

    <p><small>Developers: you may be interested that tooltips are implemented for everything, and you can render a toolbar both in 2D and 3D. So you can e.g. position a text in 3D coordinates, over an 3D object, as a tooltip. See <tt>TUIControl.TooltipStyle</tt>, <tt>TUIControl.DrawTooltip</tt>, <tt>TKamGLButton.Tooltip</tt>.</small></p></li>

  <li><p>Shadow maps PCF methods (in particular "PCF bilinear") look now better, because they know the correct shadow map size.</p></li>

  <li><p>Headlight behavior improved, e.g. you can <a href="https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/kambi_vrml_test_suite/x3d/headlight_anim.x3dv">animate headlight spot size</a>, and view3dscene "Headlight" menu item cooperates with VRML/X3D state better.</p>

  <li><p><b>Engine documentation and examples</b>:</p>
    <ul>
      <li>' . news_a_href_page('Documentation', 'vrml_engine_doc') . ': many updates, first of all a <a href="http://vrmlengine.sourceforge.net/vrml_engine_doc/output/xsl/html/chapter.scene_manager.html">new short chapter about scene manager</a> (based on my old news post).</li>
      <li>' . news_a_href_page('API reference', 'reference') . ': many improvements, a lot of documentation improved for English and PasDoc, and regenerated with <a href="http://pasdoc.sipsolutions.net/">PasDoc 0.12.1</a></li>
      <li>Nice bridge model for <tt>kambi_vrml_game_engine/examples/vrml/</tt></li>
    </ul></li>
</ul>

<p>As usual, you can test the new features by trying our <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/">nightly builds</a>.</p>
'),

    array('title' => 'Development news: drag sensors, KambiScript rotations, mouse wheel, more',
          'year' => 2010,
          'month' => 10,
          'day' => 17,
          'short_description' => '',
          'description' =>

vrmlengine_thumbs(array(
  array('filename' => 'fish.png', 'titlealt' => 'Fish and animals, by Elenka Besedova'),
  array('filename' => 'fz.png', 'titlealt' => 'Fz Quake2 player model, converted to VRML/X3D by Stephen H. France, originally by Phillip T. Wheeler'),
  array('filename' => 'projected_spotlight.png', 'titlealt' => 'Animated projector with beam and shadow, by Victor Amat'),
)) .
'Welcome to the weekly news :) As usual, remember you can try out all the improvements right now by using our <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/">nightly builds</a>.

<ul>
  <li><p>All <b>X3D dragging sensors (PlaneSensor, SphereSensor, CylinderSensor)</b> are implemented now. Very nice tools to allow user to edit portions of your 3D scenes &mdash; PlaneSensor allows moving, SphereSensor allows free rotation, CylinderSensor allows rotations constrained around an axis.</p>

    <p>I encourage you to try them &mdash; test e.g. X3D conformance models
    (<a href="http://www.web3d.org/x3d/content/examples/Conformance/Sensors/PlaneSensor/">PlaneSensor</a>,
     <a href="http://www.web3d.org/x3d/content/examples/Conformance/Sensors/SphereSensor/">SphereSensor</a>,
     <a href="http://www.web3d.org/x3d/content/examples/Conformance/Sensors/CylinderSensor/">CylinderSensor</a>)
     or VRML 97 annotated reference examples
    (<a href="http://accad.osu.edu/~pgerstma/class/vnv/resources/info/AnnotatedVrmlRef/ch3-334.htm">PlaneSensor</a>,
     <a href="http://accad.osu.edu/~pgerstma/class/vnv/resources/info/AnnotatedVrmlRef/ch3-344.htm">SphereSensor</a>,
     <a href="http://accad.osu.edu/~pgerstma/class/vnv/resources/info/AnnotatedVrmlRef/ch3-315.htm">CylinderSensor</a>)
    or our Kambi VRML test suite (see SVN, files <tt>x3d/xxx_sensor*.x3dv</tt>). They allow you to really easily add great interactivity to your VRML/X3D scenes.</p>

    <p>Also related to sensors: fixed behavior when multiple pointing-device sensors are siblings (and so should be simultaneously activated).</p></li>

  <li><p>New functions for <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/kambi_script.html#section_functions_rotation"><b>KambiScript to handle rotations</b> (SVN docs)</a>.</p></li>

  <li><p>Changing <b><tt>Transform</tt> node containing light sources</b> is greatly optimized now.</p>

    <p>This also causes a regression: if you instantiate this light source (through DEF/USE), and then try to animate it by changing it\'s Transform node &mdash; too many light instances will be updated. This is a regression (something that used to work correctly, but now doesn\'t work), but I feel it\'s justified &mdash; while previous behavior was correct, it was also awfully slow (bringing even trivial scenes to a speed of a few FPS), so the new behavior is at least usable in common cases.</p>

    <p>Various fixes along the way. Including shadow map regeneration when light changes.</p></li>

  <li><p><b>Mouse wheel</b> is supported. It is used for zoom (in camera Examine mode), scrolling text (in various message boxes), it can also be used as a (configurable) shortcut for ' . news_a_href_page('castle', 'castle') . ' actions (default is to scroll through inventory). <small>Developers: see <tt>TGLWindow.OnMouseWheel</tt> for using this in your own programs.</small></p></li>

  <li><p>Warnings after loading a model are signaled by a <b>"%d warnings" button</b> by ' . news_a_href_page('view3dscene', 'view3dscene') . '. This way warnings are still clearly visible (the button only disappears after you use it on this model), but don\'t make an "obstacle" (modal box) to viewing the model.</p></li>

  <li><p>I added to NURBS implementation status page notes about <a href="http://vrmlengine.sourceforge.net/vrml_implementation_nurbs.php#section_homogeneous_coordinates">control points in homogeneous coordinates</a>, thanks to Stephen H. France for noticing the problem and Joerg Scheurich (from White Dune) for offering an explanation.</p></li>

  <li><p>Smooth camera transitions under Windows are fixed.</p></li>

  <li><p>OrthoViewpoint improvements, to internally adjust fieldOfView to keep aspect ratio. (<a href="https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/kambi_vrml_test_suite/x3d/ortho_viewpoint.x3dv">OrthoViewpoint.fieldOfView demo</a>)</p></li>
</ul>'),

    array('title' => 'Website facelift',
          'year' => 2010,
          'month' => 10,
          'day' => 10,
          'short_description' => '',
          'description' =>

vrmlengine_thumbs(array(
  array('filename' => 'www_facelift_index.png', 'titlealt' => 'Snapshot comparing old and new index page look'),
  array('filename' => 'www_facelift_vrml_status.png', 'titlealt' => 'Snapshot comparing old and new vrml_implementation_status page look'),
)) .
'<p>As you can see, this week brings many improvements to our website. I hope it\'s now easier to navigate, and things look a little better :)</p>

<ol>
  <li><p>We have a nice header, visible at the top of every page, with most important links / sections clearly emphasized. Among other things, this avoids the previous looong index page. And makes the important but previously too-easy-to-miss links ' . news_a_href_page('"Support"', 'support') . ' and ' . news_a_href_page('"Engine" (for developers)', 'kambi_vrml_game_engine') . ' more visible.</p></li>

  <li><p>Some sections get a sidebar on the right for easier navigation. This is especially useful with ' . news_a_href_page('VRML/X3D', 'vrml_x3d') . ' section, which has a huge number of useful content especially under ' . news_a_href_page('Implementation status', 'vrml_implementation_status') . '.</p></li>

  <li><p>We also have "breadcrumbs" visible on pages deeper in the hierarchy, like ' . news_a_href_page('Shaders implementation status', 'vrml_implementation_shaders') . '. Together with header and sidebar they (hopefully) clearly show you where you are in the website.</p></li>

  <li><p>New ' . news_a_href_page('VRML/X3D', 'vrml_x3d') . ' page, an introduction to the whole VRML/X3D section, explains <i>"What is VRML / X3D"</i> in a short and friendly way. This will hopefully explain newcomers (to our engine, and/or X3D) why this 3D format is so great that I based my whole engine on it :)</p></li>

  <li>Our news are nicer now, with each ' . news_a_href_page('news', 'news') . ' post displayed on a separate page (previous "one page with all the news" was getting awfully long to load).  You get nice <i>Newer / Older</i> links and a sidebar to navigate among our news posts easily.</li>
</ol>'),

    array('title' => 'Development news: Examine improvements, smooth transitions, PlaneSensor and more',
          'year' => 2010,
          'month' => 9,
          'day' => 30,
          'short_description' => '',
          'description' =>

vrmlengine_thumbs(array(
  array('filename' => 'rendered_texture_mirror_2.png', 'titlealt' => 'Mirrors by RenderedTexture, by Victor Amat'),
  array('filename' => 'castle_siege_1.png', 'titlealt' => 'castle_siege model from DeleD sample models, converted to VRML by Stephen H. France'),
  array('filename' => 'castle_siege_shadows.png', 'titlealt' => 'castle_siege model from DeleD sample models, with shadows'),
)) .
'<p>The quest to "cleanup and optimize" all around the engine continues :) New features are listed below. As usual, you\'re welcome to test them by trying our <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/">nightly builds</a>.</p>

<ol>
  <li><p><b>Camera improvements</b>:</p>

    <ul>
      <li><p><i>Examine camera got a functionality boost</i>, and as a result some long-time troubles with switching camera modes are fixed now. Examine camera correctly honors now Viewpoint nodes, and switching camera modes preserves the current view, and switching viewpoints preserves camera mode. Thanks to Jens van Schelve for reporting this and pushing me to fix this :)</p>

        <p><small>Developers: engine has a new camera class, TUniversalCamera, that is created by default for VRML/X3D scenes and offers a functionality of both Examine and Walk navigation methods. If you previously used something like <tt>"(SceneManager.Camera as TWalkCamera)"</tt> to access Walk-specific properties, you may need to use now <tt>"(SceneManager as TUniversalCamera).Walk"</tt> to use them. Or just try to use the basic <tt>TCamera</tt> features, without downcasting to specific camera descendants.</small></p></li>

      <li><p><i>Smooth transitions</i> between viewpoints are implemented. They also follow X3D <tt>NavigationInfo.transitionType</tt>, <tt>NavigationInfo.transitionTime</tt> fields (<a href="http://www.web3d.org/x3d/specifications/ISO-IEC-19775-1.2-X3D-AbstractSpecification/Part01/components/navigation.html#NavigationInfo">X3D spec</a>).</p></li>

      <li><p><i>All camera moving and rotating speeds are now expressed inside the engine in nice units/per second</i>.</p>

        <p>Also, <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/kambi_vrml_extensions.html#section_ext_head_bobbing">headBobbingDistance is renamed into much more suitable headBobbingTime (link to SVN docs)</a>, and is also expressed in seconds now (divide by 50 to get the same behavior with old values).</p></li>
    </ul></li>

  <li><p>New sensors implemented:<br/>
    <b><tt>PlaneSensor</tt></b>
      (<a href="http://web3d.org/x3d/specifications/ISO-IEC-19775-1.2-X3D-AbstractSpecification/Part01/components/pointingsensor.html#PlaneSensor">X3D spec</a>,
       <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/vrml_implementation_pointingdevicesensor.html">support details in our SVN docs</a>,
       demos in <a href="http://vrmlengine.sourceforge.net/kambi_vrml_game_engine.php#section_svn">SVN</a> kambi_vrml_test_suite/x3d/plane_sensor*.x3dv),<br/>
    <b><tt>StringSensor</tt></b> (<a href="http://web3d.org/x3d/specifications/ISO-IEC-19775-1.2-X3D-AbstractSpecification/Part01/components/keyboard.html#StringSensor">X3D spec</a>, <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/vrml_implementation_keydevicesensor.html">support details in our SVN docs</a>, <a href="https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/kambi_vrml_test_suite/x3d/string_sensor.x3dv">demo</a>).</p></li>

  <li><p><b>Shadow maps</b> (<a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_shadow_maps">receiveShadows, shadows fields</a>) <b>for primitives</b> (<tt>Box</tt>, <tt>Sphere</tt> etc.) are fixed now (<a href="https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/kambi_vrml_test_suite/x3d/shadow_maps/primitives.x3dv">demo</a>).</p></li>

  <li><p>Victor Amat updated the demo using our <a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_rendered_texture">RenderedTexture</a> to get <b>mirrors on a flat surface</b>. See kambi_vrml_test_suite/x3d/rendered_texture/chess.x3dv in <a href="http://vrmlengine.sourceforge.net/kambi_vrml_game_engine.php#section_svn">SVN kambi_vrml_test_suite</a>.</p></li>

  <li><p>Various <b>fixes to <tt>TimeSensor</tt></b> and other stuff, thanks to Stephen H. France for reporting!</p></li>
</ol>
'),

    array('title' => 'Development news: ClipPlane, CHM docs, optimizations and more',
          'year' => 2010,
          'month' => 9,
          'day' => 18,
          'short_description' => '',
          'description' =>

vrmlengine_thumbs(array(
  array('filename' => 'jarjar.png', 'titlealt' => 'JarJar animation, made in X3D by Stephen H. France'),
  array('filename' => 'rendered_texture_output_events.png', 'titlealt' => 'RenderedTexture.rendering and ClipPlane demo: the teapot is sliced in half when rendering to the texture'),
)) .
'A lot of work happened last month, as I\'m sure <a href="http://cia.vc/stats/project/vrmlengine">SVN statistics</a> confirm :) Some highlights:

<ol>
  <li><p>VRML/X3D features implemented:

    <ul>
      <li><b>ClipPlane</b> node is handled.
      <li><b>ColorRGBA</b> node is handled. Also related VRML 1.0 Material-per-vertex/face is now much faster.<!-- (uses <tt>glColorMaterial</tt>).-->
      <li><a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/kambi_vrml_extensions.html#section_ext_rendered_texture"><b>RenderedTexture.rendering, viewing, projection</b> (link to SVN docs)</a> output events are implemented.
      <li><b>TimeSensor.enabled, cycleTime</b> are now handled correctly.
      <li><a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/kambi_vrml_extensions.html#section_ext_toggler"><b>Toggler</b> (link to SVN docs) </a> node (simple event utility) from InstantReality is handled.
    </ul>

  <li><p>Stephen H. France prepared <b><a href="http://vrmlengine.sourceforge.net/abbreviated_x3d_specs_for_users.chm">X3D specification including Kambi extensions</a> and <a href="http://vrmlengine.sourceforge.net/kambiscript_language.chm">KambiScript reference</a></b> in the CHM format. The CHM format makes them easy to browse and search. Thanks!</p>

  <li><p><b>Primitives</b>: more nodes (boxes, spheres, cones, cylinders) are now processed by converting them to <tt>IndexedFaceSet</tt> or similar low-level geometry. (This is called the <i>"proxy mechanism"</i> in sources.) And the whole mechanism is now much more efficient, so e.g. <tt>Extrusion</tt>, <tt>Teapot</tt>, NURBS curves and surfaces are processed now faster.</p>

    <p>The immediate gain from it is that Box, Cone, Cylinder, Sphere <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/kambi_vrml_extensions.html#section_ext_tex_coord">get the "texCoord" field (link to SVN docs)</a>. In particular they can use our bump mapping features, they work with multi-texturing and 3D textures fully correctly, and they can be shadow map receivers (although this last thing still needs a little work).</p>

    <!--p>Developers: this makes a little incompatible change. TVRMLShape.Geometry/State now may return something more temporary. Most code should work out-of-the-box without changes (and work faster!), but if you e.g. played with removing the geometry nodes &mdash; you should consider using TVRMLShape.OriginalGeometry node instead, see also TVRMLScene.RemoveShapeGeometry.</p-->
  </li>

  <li><p><b>Events</b>: the code responsible for changing the VRML/X3D graph (in particular, through the events) got a few refreshments. Some events work better or faster now (e.g. <tt>RenderedTexture.dimensions</tt> and <tt>depthMap</tt> can be changed through events.)</p>

    <p>A couple of large optimizations for <tt>Transform</tt> animation were implemented.</p>

  <!--li><p>Next view3dscene release will include scripts to easier setup desktop (GNOME) integration.</li-->
</ol>

<p>As always, you can test the new features before the next release by trying our <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/">nightly builds</a>.'),


    array('title' => 'view3dscene 3.6.0 release: Many shadow maps improvements',
          'year' => 2010,
          'month' => 8,
          'day' => 8,
          'short_description' => '',
          'description' =>

vrmlengine_thumbs(array(
  array('filename' => 'sunny_street_above_view.png', 'titlealt' => 'Just a screenshot with nice shadow maps'),
  array('filename' => 'sunny_street_tree_hard.png', 'titlealt' => 'Close up shadows on the tree. Notice that leaves (modeled by alpha-test texture) also cast correct shadows.'),
  array('filename' => 'rendered_texture_mirror.png', 'titlealt'=> 'Flat mirrors by RenderedTexture'),
)) .
'<p>New ' . news_a_href_page('view3dscene 3.6.0', 'view3dscene') . ' release focuses on the improvements to our <a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_shadow_maps">Shadow Maps extensions</a>:</p>

<ul>
  <li><a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_light_shadows_on_everything">X3DLightNode.shadows</a> field, to easily activate shadows on everything.</li>
  <li><a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_receive_shadows">Apperance.receiveShadows</a> field, to easily activate shadows on specific shadow receivers.</li>
  <li>Light sources\' <tt>projectionNear</tt>, <tt>projectionFar</tt> and such are automatically calculated now to suitable values, as long as you use high-level <tt>X3DLightNode.shadows</tt> or <tt>Apperance.receiveShadows</tt> fields.</li>
  <li>Incompatible changes: <tt>DirectionalLight.projectionRectangle</tt> order changed, to match standard <tt>OrthoViewpoint.fieldOfView</tt> order. Also, <tt>projection*</tt> parameters are zero by default (which indicates that they should be automatically calculated).</li>
  <li>Easy menu items to control shadow maps, see the new <i>View -&gt; Shadow Maps -&gt; ...</i> submenu.</li>
  <li>New <a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_texture_gen_projective">ProjectedTextureCoordinate</a> node for projective texturing. Can project a texture also from a viewpoint now.</li>
  <li>Extensions to <a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_head_bobbing">control head-bobbing in VRML/X3D worlds</a>.</li>
  <li>Picking and ray-tracing with orthogonal projection fixed. (See also new <a href="http://vrmlengine.sourceforge.net/rayhunter.php">rayhunter (version 1.3.2)</a> with <tt>--ortho</tt> option).</li>
  <li>See also <a href="http://vrmlengine.sourceforge.net/news.php#2010-7-9-development_news__many_shadow_maps_improvements__castle_fountain__more">previous news item</a> for some more details about new stuff implemented.
</ul>

<p>Also, <a href="http://vrmlengine.sourceforge.net/shadow_maps_x3d_slides.pdf">the slides from my Web3D 2010 talk about Shadow Maps</a> (and the <a href="http://vrmlengine.sourceforge.net/shadow_maps_x3d.pdf">paper</a>) are available now.</p>

<p>In other news: Victor Amat just send me a very nice demo that uses our <a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_rendered_texture">RenderedTexture</a> to implement beautiful mirrors on a flat surface. See the models inside <tt>x3d/rendered_texture</tt> in <a href="http://vrmlengine.sourceforge.net/kambi_vrml_test_suite.php">Kambi VRML test suite (new version 2.8.0)</a>.</p>

<p>All the shadow maps improvements are actually implemented inside our <a href="http://vrmlengine.sourceforge.net/kambi_vrml_game_engine.php">engine (new version 2.1.0)</a>.</p>
'),

    array('title' => 'Development news: Many shadow maps improvements, castle fountain, more',
          'year' => 2010,
          'month' => 7,
          'day' => 9,
          'short_description' => '',
          'description' =>

vrmlengine_thumbs(array(
  array('filename' => 'sunny_street_above_view.png', 'titlealt' => 'Just a screenshot with nice shadow maps'),
  array('filename' => 'sunny_street_tree_hard.png', 'titlealt' => 'Close up shadows on the tree. Notice that leaves (modeled by alpha-test texture) also cast correct shadows.'),
  array('filename' => 'sunny_street_tree_pcf16.png', 'titlealt' => 'Close up shadows on the tree, with Percentage Closer Filtering.'),
  array('filename' => 'depths_camera_mapped.png', 'titlealt' => 'Shadow map mapped over the scene'),
  array('filename' => 'castle_fountain_1.png', 'titlealt' => 'Fountain water'),
  array('filename' => 'castle_fountain_2.png', 'titlealt' => 'Fountain close-up view'),
), 2) .
'<p>First of all, my paper <a href="http://vrmlengine.sourceforge.net/shadow_maps_x3d.pdf">Shadow maps and projective texturing in X3D</a> got accepted for the <a href="http://conferences.web3d.org/web3d2010/">Web3D 2010 Conference</a>. Wee, I\'m going to Los Angeles :) This paper presents our new shadow mapping extensions, with many improvements over the old ones previously implemented in our engine. You can read the paper online, you can also <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/kambi_vrml_extensions.html#section_ext_shadow_maps">read the new shadow mapping extensions documentation (from our nightly snapshots)</a>.</p>

<p>The improvements already implemented are:</p>

<ul>
  <li>First of all, <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/kambi_vrml_extensions.html#section_ext_receive_shadows"><tt>Apperance.receiveShadows</tt> field for nice and comfortable shadows usage</a>. This very simple extension is what I hope to be ultimately used in 90% of the simple cases when you "just want shadows".</li>
  <li>Easy menu items to activate <i>Percentage Closer Filtering</i> (4, 16, 4 bilinear) and visualize shadow maps for scenes using the <tt>receiveShadows</tt> field. Look at the new <i>View -&gt; Shadow Maps -&gt; ...</i> menu items.</li>
  <li>New <tt>ProjectedTextureCoordinate</tt> node, that replaces deprecated now <tt>TextureCoordinateGenerator.mode = "PROJECTION"</tt>.</li>
  <li><a href="http://www.punkuser.net/vsm/">Variance Shadow Maps</a> are also implemented. Although their implementation is not optimal yet, and should be treated as experimental. You can easily turn them on by <i>View -&gt; Shadow Maps -&gt; Variance Shadow Maps</i> menu.</li>
</ul>

<p>For now, you can test these features by using <tt>view3dscene</tt> from our <a href="http://michalis.ii.uni.wroc.pl/~michalis/vrmlengine-snapshots/">nightly builds</a>.</p>

<p>You may also be interested in our shadow maps testing scene "sunny_street", you can checkout it from SVN url <a href="https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/vrml_engine_doc/shadow_maps_x3d/sunny_street/">https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/vrml_engine_doc/shadow_maps_x3d/sunny_street/</a>.</p>

<!-- teaser -->

<p>Other features implemented:</p>

<ul>
  <li>Extensions to <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/kambi_vrml_extensions.html#section_ext_head_bobbing">control head-bobbing in VRML/X3D worlds (docs from nightly builds)</a>.</li>
  <li>view3dscene <i>Edit -&gt; Merge Close Vertexes</i> menu item, that makes close vertexes to be perfectly equal.</li>
  <li><a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_teapot">Teapot</a> mesh is much improved, thanks go to Victor Amat.</li>
  <li>Picking and ray-tracer in orthogonal projection (<a href="http://www.web3d.org/x3d/specifications/ISO-IEC-19775-1.2-X3D-AbstractSpecification/Part01/components/navigation.html#OrthoViewpoint">like by OrthoViewpoint</a>) fixed.</li>
  <li>Workaround nasty <a href="http://ati.cchtml.com/show_bug.cgi?id=1815">fglrx bug</a>, thanks Simon for <a href="https://sourceforge.net/apps/phpbb/vrmlengine/viewtopic.php?f=3&amp;t=14">reporting</a>.</li>
  <li>Better menu behavior with GTK2 backend.</li>
  <li>Our procedural terrain demo (<tt>examples/vrml/terrain/</tt> in sources) can export the terrain to X3D (<tt>ElevationGrid</tt>) now.</li>
  <li>Support IMPORT/EXPORT for VRML 2.0 (97) too. Although it\'s defined only in X3D spec, it\'s so useful that I enable it also for VRML 2.0.</li>
</ul>

<p>In unrelated news, the quest to release castle 1.0.0 is ongoing (even if terribly delayed). Remember, I wanted to add some eye-candy to "Fountain" level for this? Well, part of the job is done, see the screenshot on the right for a nice water pouring from the fountain.</p>
'),

    array('title' => 'view3dscene 3.5.2 release: IMPORT/EXPORT and bugfixes',
          'year' => 2010,
          'month' => 4,
          'day' => 18,
          'short_description' => '',
          'description' =>

vrmlengine_thumbs(array(
  array('filename' => 'ddracer_scene85.png', 'titlealt' => 'scene85'),
  array('filename' => 'ddracer_t128.png', 'titlealt' => 't128'),
  array('filename' => 'ddracer_t603coupe.png', 'titlealt' => 't603coupe'),
)) .
'<p>New ' . news_a_href_page('view3dscene 3.5.2', 'view3dscene') . ' is released today:</p>

<ul>
  <li><p>New feature in this release is the support for <a href="http://www.web3d.org/x3d/specifications/ISO-IEC-19775-1.2-X3D-AbstractSpecification/Part01/components/networking.html#IMPORTStatement">X3D IMPORT and EXPORT statements</a>.

    <p>This allows to attach routes to VRML/X3D nodes inside inline files, thus allowing communication between the nodes inside and outside of the inlined file. Available in both classic and XML encodings. Some more <a href="http://vrmlengine.sourceforge.net/vrml_implementation_networking.php">support details is here</a>.

  <li><p>Particular setup with nested PROTOs was expanded incorrectly, fixed now.

    <p>Thanks to David Rehor for reporting, <a href="http://tatraportal.com/drracer/">check out his VRML models here</a> ! (And on the screenshots to the right :) )

  <li><p>Crashes on menu commands <i>Edit -&gt; Remove Geometry Node / Face</i> are now fixed.
</ul>

<p>Accompaying ' . news_a_href_page('engine 2.0.3', 'kambi_vrml_game_engine') . ' is also released. All above view3dscene features/fixes are actually implemented mostly in the engine. Also, for the programmers: you will find useful .lpi files to compile every engine example from Lazarus. Also program names now use standard Lazarus extension .lpr. (These last improvements were actually already "silently" released in engine version 2.0.2 shortly before 2.0.3.)

<p>Also ' . news_a_href_page('malfunction 1.2.6', 'malfunction') . ' is released, this fixes a crash when opening some levels (caused by the same problem as view3dscene\'s <i>Edit -&gt; Remove Geometry Node / Face</i> crashes).'),


    array('title' => 'view3dscene 3.5.1 bugfix release, glinformation 1.2.0',
          'year' => 2010,
          'month' => 4,
          'day' => 5,
          'short_description' => '',
          'description' =>

vrmlengine_thumbs(array(
  array('filename' => 'view3dscene_bsuit.png', 'titlealt' => 'view3dscene showing tremulous battlesuit from MD3'),
)) .
'<p>A bug crawled into view3dscene 3.5.0 release: opening kanim and MD3 files usually failed with <i>"Invalid floating point operation"</i>. Therefore, we quickly release a fix in ' . news_a_href_page('view3dscene 3.5.1', 'view3dscene') . '. By the way, <i>View-&gt;Blending...</i> menu options are rearranged and <i>Help-&gt;OpenGL information</i> looks better now.</p>

<p>Also ' . news_a_href_page('glinformation 1.2.0', 'glinformation') . ' (previously known as <tt>glcaps</tt>) is released: various improvements to the output (it\'s the same text as <i>Help->OpenGL information</i> in view3dscene) and packaging.</p>

<p>' . news_a_href_page('Engine 2.0.1', 'kambi_vrml_game_engine') . ' is also released with these fixes.</p>'),

    array('title' => 'Release: view3dscene 3.5.0, engine 2.0.0, others',
          'year' => 2010,
          'month' => 3,
          'day' => 30,
          'short_description' => '',
          'description' =>

vrmlengine_thumbs(array(
  array('filename' => 'nurbs_curve_interpolators.png', 'titlealt' => 'Animating along the NURBS curve (NurbsPositionInterpolator and NurbsOrientationInterpolator)'),
  array('filename' => 'nurbs_surface_interpolator.png', 'titlealt' => 'Animating along the NURBS surface (NurbsSurfaceInterpolator)'),
  array('filename' => 'multiple_viewports_dynamic_world.png', 'titlealt' => 'multiple_viewports: interactive scene, with shadows and mirror'),
  array('filename' => 'terrain_nice_fog.png', 'titlealt' => 'Terrain - valley view with a hint of fog'),
), 2) .

'<p>Today we release a ' . news_a_href_page('grand new 2.0.0 version of the Kambi VRML game engine', 'kambi_vrml_game_engine') . ' and a new ' . news_a_href_page('version 3.5.0 of our main tool, view3dscene', 'view3dscene') . '. Other minor programs here are also updated, to bring bugfixes to them. Changes:</p>

<p><b>User-visible features</b>:</p>

<ul>
  <li>' . news_a_href_page('NURBS support', 'vrml_implementation_nurbs') . '. Most of the X3D NURBS component (level&nbsp;1) is implemented, this includes curves, surfaces and interpolators. VRML 97 NURBS nodes are also handled.</li>
  <li>Major bugfixes to the GTK 2 (Unix) backend and shadow maps handling.</li>
  <li>Countless small bugfixes and improvements.</li>
</ul>

<p><b>Programmer-visible engine features</b>:</p>

<ul>
  <li>Scene manager (<tt>TKamSceneManager</tt>), a manager of the 3D world.</li>
  <li>Custom viewports (<tt>TKamViewport</tt>) easily usable with our scene manager.</li>
  <li>2D controls framework: <tt>TKamGLButton</tt>, <tt>TKamGLImage</tt>, better <tt>TGLMenu</tt> and more. Viewports are also 2D controls.</li>
  <li>Engine sources reorganized into more intuitive <tt>src/</tt>, <tt>examples/</tt> etc. directories.</li>
  <li>Much more components registered on the Lazarus palette. (This will be extended in next releases.)</li>
  <li>Engine is licensed now on the terms of <a href="http://vrmlengine.sourceforge.net/kambi_vrml_game_engine.php#section_license">the GNU Lesser General Public License (with "static linking exception")</a>.</li>
</ul>

<p>For more details about the changes, see ' . news_a_href_page('the news archive', 'news'). '.</p>

<p>For people waiting for new ' . news_a_href_page('castle 1.0.0', 'castle') . ' release: not yet, but should happen very soon.</p>'),

    array('title' => 'Custom viewports, engine 2.0.0 release very soon',
          'year' => 2010,
          'month' => 3,
          'day' => 27,
          'short_description' => '',
          'description' =>

vrmlengine_thumbs(array(
  array('filename' => 'multiple_viewports_teapot.png', 'titlealt' => 'multiple_viewports: simple teapot scene'),
  array('filename' => 'multiple_viewports_tower_mirror_raptor.png', 'titlealt' => 'multiple_viewports: scene with raptor animation and mirror by GeneratedCubeMapTexture'),
  array('filename' => 'multiple_viewports_shadows.png', 'titlealt' => 'multiple_viewports: animated shadows by shadow volumes'),
  array('filename' => 'multiple_viewports_dynamic_world.png', 'titlealt' => 'multiple_viewports: interactive scene, with shadows and mirror'),
), 2) .

'<p>You can now have many viewports on the 2D window to observe your 3D world from various cameras. You can make e.g. split-screen games (each view displays different player), 3D modeling programs (where you usually like to see the scene from various angles at once), or just show a view from some special world place (like a security camera).</p>

<p>Your viewports may be placed in any way you like on the screen, they can even be overlapping (one viewport partially obscures another). Each viewport has it\'s own dimensions, own camera, but all viewports share the same 3D world. Each viewport has also it\'s own rendering methods, so you can derive e.g. a specialized viewport that always shows wireframe view of the 3D world.</p>

<p>This very nice feature is implemented thanks to the scene manager framework. The scene manager itself also acts as a viewport (if <tt>DefaultViewport</tt> is true), which is comfortable for simple programs where one viewport is enough. When <tt>DefaultViewport</tt> is false, scene manager is merely a container for your 3D world, referenced by custom viewports (<tt>TKamViewport</tt> classes).</p>

<p>See the screenshots on the right and <tt>kambi_vrml_game_engine/examples/vrml/multiple_viewports.lpr</tt> example program in the SVN for demo.</p>

<!-- teaser -->

<p>Other improvements include new button rendered in the OpenGL (<tt>TKamGLButton</tt> in <tt>kambi_vrml_game_engine/src/ui/opengl/glcontrols.pas</tt> unit), you can see it on the screenshots too. This is the start of a promised 2D controls library for the engine. The idea is that such button may be easily themed for your OpenGL game, to match game mood and graphics.</p>

<p>Also there\'s a <tt>TKamGLImage</tt> control, and lot\'s of bugfixes stabilizing engine 2.0.0. It\'s pretty much finished now &mdash; expect engine 2.0.0 and view3dscene 3.5 releases very shortly :)</p>'),

    array('title' => 'Terrain demo much extended',
          'year' => 2010,
          'month' => 3,
          'day' => 11,
          'short_description' => '',
          'description' =>

vrmlengine_thumbs(array(
  array('filename' => 'terrain1.png', 'titlealt' => 'Terrain 1'),
  array('filename' => 'terrain2.png', 'titlealt' => 'Terrain 2'),
  array('filename' => 'terrain_wire_lod.png', 'titlealt' => 'Terrain - wireframe view showing our simple LOD approach'),
  array('filename' => 'terrain_nice_fog.png', 'titlealt' => 'Terrain - valley view with a hint of fog'),
  array('colspan' => 2,
    'html' => (!HTML_VALIDATION ? '<object width="370" height="227"><param name="movie" value="http://www.youtube.com/v/9qx-Ry2PRWM&amp;hl=pl_PL&amp;fs=1&amp;"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/9qx-Ry2PRWM&amp;hl=pl_PL&amp;fs=1&amp;" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="370" height="227"></embed></object>' : '')),
), 2) .

'<p>Our procedural terrain demo (see <tt>kambi_vrml_game_engine/examples/vrml/terrain</tt> in <a href="http://vrmlengine.sourceforge.net/kambi_vrml_game_engine.php#section_svn">SVN</a>) got a lot of improvements this week:</p>

<ul>
  <li><i>Heterogeneous</i> terrain (idea from <a href="http://www.kenmusgrave.com/dissertation.html">Ken Musgrave</a>) implemented, this makes more realistic terrain (smooth valleys, noisy mountains).</li>
  <li>Terrain is rendered with nice blended texture layers, normals are calculated for fake lighting, fog may be used, all by simple GLSL shader.</li>
  <li>Simple LOD approach for rendering is used, this is an ultra-dumbed-down version of <a href="http://research.microsoft.com/en-us/um/people/hoppe/geomclipmap.pdf">geometry clipmaps</a>. The way I simplified this is unfortunately painfully visible as LOD "popping" artifacts. But, hey, at least I can view really large terrain.</li>
  <li>You can test various noise interpolation methods, including <i>Catmull-Rom splines</i>.</li>
  <li>2D noise can be blurred, which (may) improve the terrain look.</li>
  <li>You can switch camera to <i>Walk</i> mode.</li>
  <li>You can add a heighmap from grayscale image to generated terrain.</li>
  <li>Rendering uses VBOs for speed.</li>
</ul>

<p>Finally, the programmers may be interested in my <a href="https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/kambi_vrml_game_engine/examples/vrml/terrain/TERRAIN_GENERATION_NOTES.txt">my notes and links about basic terrain generation methods</a>.</p>'),

    array('title' => 'Cloth animation with bump mapping',
          'year' => 2010,
          'month' => 2,
          'day' => 26,
          'short_description' => '',
          'description' =>

(!HTML_VALIDATION ?
'<table align="right"><tr><td>
  <object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/5DTu9tEn44g&amp;hl=pl_PL&amp;fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/5DTu9tEn44g&amp;hl=pl_PL&amp;fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object>
 </td></tr></table>' : '') .
'<p>To sweeten the wait for engine 2.0.0 (and castle 1.0.0) releases: <a href="http://www.youtube.com/watch?v=5DTu9tEn44g">here\'s a short demo movie</a> with a cloth animation (cloth simulated and animated in <a href="http://www.blender.org/">Blender</a>) rendered using our engine with bump mapping. The point here is that both 3D meshes and lights may be animated in real-time and bump mapping works perfectly.</p>

<p>This was always possible, but some recent improvements made this much easier. Namely:</p>

<ol>
  <li>Our <tt>examples/vrml/bump_mapping</tt> demo now works with animated objects without any trouble.</li>
  <li>Our <tt>examples/vrml/tools/kanim_to_interpolators</tt> is now slightly more general converter from ' . news_a_href_page('KAnim format', 'kanim_format') . ' to VRML/X3D. This means it\'s possible to make "normal" animated VRML/X3D models by ' . news_a_href_page('exporting from Blender to kanim', 'blender_stuff') . ', then converting kanim to VRML/X3D. Convertion kanim-&gt;VRML/X3D is totally lossless, so the whole setup works quite flawlessly &mdash; at least for this simple cloth demo.</li>
</ol>

<p>The source model is in SVN, in <tt>kambi_vrml_test_suite/vrml_2/kambi_extensions/bump_mapping/cloth/</tt>. You can open it with the bump_mapping example (from our engine sources) or view3dscene.</p>'),

    array('title' => 'More engine 2.0 news: all examples and &quot;The Castle&quot; use scene manager',
          'year' => 2010,
          'month' => 2,
          'day' => 4,
          'short_description' => '',
          'description' =>

'<p>Nearly all existing programs (examples and normal games) are now happily converted to the new <i>scene manager</i> approach. Scene manager interface got a lot of improvements by the way, <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/KambiSceneManager.TKamSceneManager.html">check the docs</a>. This means that engine 2.0.0 is coming along nicely and will soon be released :)</p>

<p>In particular, ' . news_a_href_page('The Castle', 'castle') . ' source code is converted to use the scene manager. This means that finally you can construct <i>"The Castle"</i> interactive levels by VRML/X3D events, using VRML/X3D time, touch sensors, key sensors, proximity sensors (see <a href="http://www.web3d.org/x3d/specifications/ISO-IEC-19775-1.2-X3D-AbstractSpecification/index.html">X3D spec</a> for sensor nodes docs), scripts in ' . news_a_href_page('KambiScript', 'kambi_script') . ' and such. To celebrate this, I plan to add a little eye-candy to castle\'s "Fountain" level, and then officially release the final <b>"The Castle 1.0.0"</b> along with <b>engine 2.0.0</b>. After that, work on "The Castle 2" may begin :)</p>

<p>More teasers about the <i>Castle 1.0.0</i> release and plans for <i>Castle 2</i> in later post hopefully next week.</p>

<p>Getting back to the engine work, one important news for developers:  <!-- teaser --> I renamed one of the most important new classes: what was <tt>TBase3D</tt> is now called "<a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/Base3D.T3D.html">T3D</a>". (Reasoning: this is a shorter and better name. "Base" prefix was just uncomfortable, esp. since descendants were no longer "base" 3D objects but they were have to be called "TBase3D" in many places.)</p>

<p>Also, some other classes (<tt>T[Custom]Translated/List3D</tt>) renamed to follow <tt>T3DXxx</tt> pattern, like <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/Base3D.T3DList.html">T3DList</a> and <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/GL3D.T3DTranslated.html">T3DTranslated</a>. That is, I moved the "3D" part of the name to the beginning. (Reasoning: looks just better, and is consequent with usage of other common prefixes like TKamXxx and TVRMLXxx classes. General rule is that "common part should be the prefix, not suffix", as this is easier to see (and presents itself better when identifiers are sorted alphabetically in API docs etc.).)</p>

<p>These renames concern only new classes, not released anyway yet (and we know anyway that engine 2.0.0 will break some compatibility, so let\'s take this opportunity and "get" the important classes names right, Ok? :) ).</p>

<p>Note: I edited the previous news post (the one ~2 weeks ago) to reflect these renames. I don\'t usually edit my news posts after publishing :), but in this case it was important to fix it. Otherwise some classes and code mentioned in the previous news article would not exist.</p>'),

    array('title' => 'Development news: engine 2.0',
          'year' => 2010,
          'month' => 1,
          'day' => 26,
          'short_description' => '',
          'description' =>

vrmlengine_thumbs(array(
  array('filename' => 'scene_manager_demos_1.png', 'titlealt' => 'Screenshot from scene_manager_demos &mdash; two VRML scenes and one precalculated animation at once'),
  array('filename' => 'scene_manager_demos_2.png', 'titlealt' => 'Another screenshot from scene_manager_demos'),
)) . '

<p>During the last weeks I did a lot of work on the engine API. Especially the new <b>Scene Manager</b> approach makes quite a revolutionary change, and there\'s also <i>2D Controls manager</i>, better <i>Lazarus components</i> and many more. Together, I feel these are so important for developers using my engine that the next engine version will be bumped to proud <i>2.0.0</i> :) To be released next month.</p>

<p>Not much noticeable for a normal user (sorry; although there are various fixes and improvements here and there). Below news post turned out quite long, and I\'ll keep passing you links to the <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/">current API reference of SVN code</a>, so... developers: keep reading if you\'re interested, and others: well, this may get dirty, so you can stop reading now :)</p>

<!-- teaser -->

<ol>
  <li>
    <p>The number one reason behind all these changes is that it was too difficult to add new stuff to your window. This concerned 3D stuff, mainly the two most important classes of our engine: <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/VRMLGLScene.TVRMLGLScene.html">TVRMLGLScene</a> and <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/VRMLGLAnimation.TVRMLGLAnimation.html">TVRMLGLAnimation</a>. And it also concerned 2D controls, for example our menu with fancy GUI sliders: <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/GLMenu.TGLMenu.html">TGLMenu</a> (this was used in ' . news_a_href_page('castle', 'castle') . ' and recent <tt>examples/vrml/terrain</tt> demo).</p>

    <p><b>The goal</b>: the goal (already achieved :) ) is to allow you to make a full 3D model viewer / VRML browser (with collisions, fully optimized rendering etc.) by this simple code:</p>

<pre style="background: #DDD">
var
  Window: TGLUIWindow;
  SceneManager: TKamSceneManager;
  Scene: TVRMLGLScene;
begin
  Scene := TVRMLGLScene.Create(Application { Owner that will free the Scene });
  Scene.Load(\'my_scene.x3d\');
  Scene.Spatial := [ssRendering, ssDynamicCollisions];
  Scene.ProcessEvents := true;

  SceneManager := TKamSceneManager.Create(Application);
  SceneManager.Items.Add(Scene);
  SceneManager.MainScene := Scene;

  Window := TGLUIWindow.Create(Application);
  Window.Controls.Add(SceneManager);
  Window.InitAndRun;
end.
</pre>

    <p>(The source code of this is in <a href="https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/kambi_vrml_game_engine/examples/vrml/scene_manager_basic.lpr">scene_manager_basic</a> demo inside engine examples. There\'s also more extensive demo in the <a href="https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/kambi_vrml_game_engine/examples/vrml/scene_manager_demos.lpr">scene_manager_demos</a> sample.)</p>

    <p>This looks nice a relatively straighforward, right? You create 3D object (<tt>Scene</tt>), you create 3D world (<tt>SceneManager</tt>), and a window to display the 3D world (<tt>Window</tt>). What is really the point here is that you immediately know how to add a second 3D object: just create <tt>Scene2</tt>, and add it to <tt>SceneManager.Items</tt>.</p>

    <p>The idea of <i>scene manager</i> will soon be explained in more detail, for now let\'s go a litle back in time and see what was wrong without the scene manager:</p>

    <p><b>The trouble</b>: our existing classes were nicely encapsulating some functionality (showing menu, rendering VRML etc.) but they <i>were a pain to add to your window / 3D world</i>.</p>

    <p>You could use something like <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/GLWindowVRMLBrowser.TGLWindowVRMLBrowser.html">TGLWindowVRMLBrowser</a> (or equivalent Lazarus component, <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/KambiVRMLBrowser.TKamVRMLBrowser.html">TKamVRMLBrowser</a>) but these offered too little flexibility. They were made to allow easily loading <i>only one scene</i>.</p>

    <p>For anything more complicated, you had to directly create your <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/VRMLGLScene.TVRMLGLScene.html">TVRMLGLScene</a> etc. classes, and (this is the key point) to handle various window / camera / callbacks to connect the 3D scene with a camera and with a window. For example, you had to register window <tt>OnIdle</tt> callback to increment VRML scene time (to keep animations running etc.) You had to register <tt>OnKeyDown</tt>, <tt>OnKeyUp</tt> and pass key events to VRML scene (to make VRML sensors work), <tt>OnMouseXxx</tt> callbacks had to be passed to handle VRML touch sensors, <tt>OnDraw</tt> to handle scene rendering. You also had to register camera callbacks (<a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/Cameras.TWalkCamera.html#OnMoveAllowed"><tt>OnMoveAllowed</tt></a>, <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/Cameras.TWalkCamera.html#OnGetCameraHeight"><tt>OnGetCameraHeight</tt></a>) to connect collision detection with your 3D scene. And the list goes on...</p>

    <p>As a witness to all these troubles, have a look at implementation of <tt>GLWindowVRMLBrowser</tt> or (nearly identical) implementation of <tt>KambiVRMLBrowser</tt> is last released engine version. They are long and clumsy. In current SVN code, they are nice, short and clean, and also more flexible (can handle many 3D objects, not just 1 VRML scene).</p>

    <p><b>The solution(s):</b> There are actually two solutions, one directed at 3D objects (living in the 3D world), the other at 2D controls (merely taking some space on the 2D window) . They are quite similar, and nicely playing with each other:</p>

    <ol>
      <li><p><i>For 3D objects</i>: we have a base class <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/Base3D.T3D.html">T3D</a> from which <i>all</i> other 3D objects are derived. For example, <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/VRMLGLScene.TVRMLGLScene.html">TVRMLGLScene</a> and <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/VRMLGLAnimation.TVRMLGLAnimation.html">TVRMLGLAnimation</a> are both descendants of <tt>T3D</tt> now. There are some other helper 3D objects (<a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/Base3D.T3DList.html">T3DList</a> - list of other 3D objects, and <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/GL3D.T3DTranslated.html">T3DTranslated</a> - translated other 3D object). And the real beauty is that you can easily derive your own <tt>T3D</tt> descendants, just override a couple methods and you get 3D objects that can be visible, can collide etc. in 3D world.</p>

        <p>Now, what to do with your 3D objects? Add them to your 3D world, of course. The new great class that is fully implemented now is the <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/KambiSceneManager.TKamSceneManager.html">TKamSceneManager</a>. In every program you create an instance of this class (or your own dencendant of <tt>TKamSceneManager</tt>), and you add your 3D objects to the scene manager. Scene manager keeps the whole knowledge about your 3D world, as a tree of <tt>T3D</tt> objects. After adding your whole 3D world to the scene manager, you can add the scene manager to <tt>Controls</tt> (more on this next), and then scene manager will receive all necessary events from your window, and will pass them to all interested 3D objects. Also scene manager connects your camera, and defines your viewport where 3D world is rendered through this camera.</p>
      </li>

      <li><p><i>For 2D controls</i>: quite similar solution is used, although with some details different. All stuff that had to receive window events must derive from base <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/UIControls.TUIControl.html">TUIControl</a> class. This means that <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/GLMenu.TGLMenu.html">TGLMenu</a> is now a descendant of <tt>TUIControl</tt>, and in the future I would like to have a small library of generally-usable simple 2D controls available here. Also note that <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/KambiSceneManager.TKamSceneManager.html">TKamSceneManager</a> is <tt>TUIControl</tt> descendant, since scene manager by default acts as a viewport (2D rectangle) through which you can see your 3D world. (Possibility to easily add custom viewports from the same scene manager is the next thing on my to-do list.)</p>

        <p>To actually use the <tt>TUIControl</tt>, you add it to the window\'s <tt>Controls</tt> list. If you use Lazarus component, then you\'re interested in <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/KambiGLControl.TKamOpenGLControl.html#Controls">TKamOpenGLControl.Controls</a> list. If you use our own window library, you\'re intersted in the <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/GLWindow.TGLUIWindow.html#Controls">TGLUIWindow.Controls</a>. Once control is added to the controls list, it will automatically receive all interesting events from our window.</p>
      </li>
    </ol>

    <p>That\'s it, actually. Thank you for reading this far, and I hope you\'ll like the changes &mdash; you can try SVN source code already and let me know how it works for you in practice.</p>

  </li>

  <li><p>Other changes:</p>

    <ul>
      <li>
        <p>The <i>Lazarus integration of the engine is better</i>, with many old classes reworked as components. This is also tied to the previous change, because both <tt>T3D</tt> and <tt>TUIControl</tt> are descendants of <tt>TComponent</tt> &mdash; which means that important classes of our engine, like <tt>TVRMLGLScene</tt>, and also the cameras are now components that can be registered on Lazarus palette.</p>

        <p>This is not yet finished: some important properties of these classes are not published, or not possible to design from the IDE. For example, you cannot add items to the <tt>TKamOpenGLControl.Controls</tt> list yet from the IDE. This doesn\'t limit your possibilities, it only means that you\'ll have to do some work by writing source code as opposed to just clicking in Lazarus. Things for sure are already a lot better than in previous engine release.</p>

        <p>Mouse look is now also available in Lazarus <tt>TKamOpenGLControl</tt> component.</p>
      </li>

      <li><p><tt>T3D/TUIControl</tt> give various improvements to all 2D/3D objects. For examples, <tt>TVRMLGLScene</tt> automatically sets cursor to "hand" when it\'s over a touch sensor. In fact, every <tt>T3D</tt> and <tt>TUIControl</tt> can affect cursor by <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/UIControls.TUIControl.html#Cursor">Cursor</a> property.</p></li>

      <li><p>If you do not explicitly create a camera for the scene manager, a suitable one is automatically created, see <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/KambiSceneManager.TKamSceneManager.html#Camera">TKamSceneManager.Camera</a>.</p></li>

      <li><p>All "navigator" classes, fields etc. are renamed to "camera". So e.g. <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/Cameras.TExamineCamera.html">TExamineCamera</a> is just a new name for our good old <tt>TExamineNavigator</tt> and such.</p>

        <p>Old names <i>navigator</i> were coined to emphasize the fact that they do not have to represent "real" camera that is used to display the 3D scene on the screen, they can be used to manipulate any 3D object of your scene. For example <tt>shadow_fields</tt> demo uses 4 examine navigators to manipulate various scene elements, <tt>rift</tt> uses special walk navigator to represent position of the player in the room, etc. But I decided that the name is just confusing, as most of the time you use this as a "normal camera". Advanced users can probably grasp the fact that in fact "camera" doesn\'t have to be used to display whole scene from the screen.</p></li>

      <li><p>GTK 2 backend of our <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/GLWindow.TGLWindow.html">TGLWindow</a> class was much reworked. Namely, 1. we no longer use GTK\'s <tt>gtk_idle_add</tt> to implement our <tt>OnIdle</tt> callbacks and 2. GTK\'s "expose" signal no longer calls directly <tt>OnDraw</tt> callback . These caused an endless stream of troubles with stability, related to GTK idle events priorities. Our new solution is much simpler, solving some recent problems and removing ugly workarounds for the old ones.

        <p>For more in-depth discussion of past problems, reasonings and solutions, see the document <a href="https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/kambi_vrml_game_engine/src/glwindow/gtk/why_not_using_gtk_idle.txt">why_not_gtk_idle.txt</a>.
      </li>

      <li><p>A minor improvements to the <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/GLWindow.TGLWindow.html">TGLWindow</a> are the <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/GLWindow.TGLWindow.html#MessageOK">MessageOK</a> and the <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/reference/html/GLWindow.TGLWindow.html#MessageYesNo">MessageYesNo</a> methods, proving native-looking (GTK, WinAPI etc.) message boxes.</p></li>

      <li><p>Oh, and f you\'re trying to compile the engine with newest FPC 2.4.0, please use engine SVN source for now. Current released tar.gz sources compile only with &lt;= 2.2.4. This problem will of course disappear after new engine is released.</p></li>
    </ul>

  </li>
</ol>'),


    array('title' => 'News - terrain demo, large engine layout changes',
          'year' => 2009,
          'month' => 12,
          'day' => 21,
          'guid' => '2009-12-21',
          'short_description' => '',
          'description' =>

vrmlengine_thumbs(array(
  array('filename' => 'terrain_1.png', 'titlealt' => 'Terrain from noise'),
  array('filename' => 'terrain_2.png', 'titlealt' => 'Terrain from SRTM file'),
)) . '<ol>
  <li>
    <p>There\'s a new demo in engine <a href="http://vrmlengine.sourceforge.net/kambi_vrml_game_engine.php#section_svn">SVN sources</a>: <tt>terrain</tt> (look inside <tt>kambi_vrml_game_engine/examples/vrml/terrain/</tt> directory). It shows basic procedural terrain generation. Uses cosine interpolated noise, summed by <i>Fractional Brownian Motion</i> (which is a fancy way of saying "sum a few scaled noise functions"&nbsp;:)&nbsp;).</p>

    <p>It can also load a terrain data from <a href="http://www2.jpl.nasa.gov/srtm/">SRTM (.hgt files)</a> (for example, <a href="http://netgis.geo.uw.edu.pl/srtm/Europe/">sample files for Europe are here</a>). And it can display a terrain defined by mathematical expression, like <i>sin(x*10) * sin(y*10)</i> (see <a href="http://vrmlengine.sourceforge.net/kambi_script.php">KambiScript language reference</a> for full syntax and functions available for math expressions).</p>

    <p>If you\'re interested in some background, <a href="http://freespace.virgin.net/hugo.elias/models/m_perlin.htm">this is the simplest introduction to "making noise"</a> (although beware that it\'s actually not about Perlin noise :), Perlin noise is a "gradient noise", not covered there).</p>

    <p>I would like to extend this <tt>terrain</tt> demo to something much larger (infinite terrain rendering, render with normals, cover with a couple layers of textures, add water surface, maybe render with Precomputed Radiance Transfer (taken from my other demo) etc.). For now, it\'s a start :)</p>
  </li>

  <li>
    <p>Developers will note large changes in the layout of <tt>kambi_vrml_game_engine</tt> archive (and SVN directory). (links below point to appropriate <a href="http://vrmlengine.svn.sourceforge.net/viewvc/vrmlengine/">viewvc</a> URL, for your convenience)</p>

    <ol>
      <li>All "core" sources are moved to the <a href="http://vrmlengine.svn.sourceforge.net/viewvc/vrmlengine/trunk/kambi_vrml_game_engine/src/"><tt>src/</tt> subdirectory</a>, to keep them separate from other stuff (packages, doc, tests etc.).

      <li><a href="http://vrmlengine.svn.sourceforge.net/viewvc/vrmlengine/trunk/kambi_vrml_game_engine/examples/"><tt>examples/</tt> subdirectory</a> was moved to the top. I should have done this a long time ago.  If you want to look at VRML demos, now you just go to <tt>examples/vrml/</tt> subdirectory, which is hopefully obvious to new developers. (Previously, you had to dig into cryptically-named <tt>3dmodels.gl/examples/</tt>)

      <!-- teaser </li></ol></li></ol> -->

      <li>Also, some subdirectory names changes, and some units moved around.
        <ul>
          <li>We have new <a href="http://vrmlengine.svn.sourceforge.net/viewvc/vrmlengine/trunk/kambi_vrml_game_engine/src/glwindow/">subdirectory for <tt>glwindow</tt></a> specific stuff (since the distinction what is for GLWindow, and what is not, is important to many developers; e.g. if you always want to use Lazarus OpenGL control, then GLWindow isn\'t really useful for you).
          <li>We have new <a href="http://vrmlengine.svn.sourceforge.net/viewvc/vrmlengine/trunk/kambi_vrml_game_engine/src/ui/">subdirectory for <tt>ui</tt></a>. This contains now the navigator and GLMenu &mdash; both things were available already, but now are implemented as <tt>TUIControl</tt> descendants, handled in more uniform fashion. In the future, I want to extend this, to make more OpenGL controls this way. The very scene manager may be treated as such "control" one day.
          <li>I noticed that the most important directories of our engine had a little cryptic naming: 3dgraph, 3dmodels, 3dmodels.gl. After a little shuffling, the new names are <a href="http://vrmlengine.svn.sourceforge.net/viewvc/vrmlengine/trunk/kambi_vrml_game_engine/src/3d/">3d</a>, <a href="http://vrmlengine.svn.sourceforge.net/viewvc/vrmlengine/trunk/kambi_vrml_game_engine/src/vrml/">vrml</a>, <a href="http://vrmlengine.svn.sourceforge.net/viewvc/vrmlengine/trunk/kambi_vrml_game_engine/src/vrml/opengl/">vrml/opengl/</a> &mdash; this should make things much clearer.
        </ul>
      <li>A minor thing is also that units Glw_Navigated, Glw_Win, Glw_Demo are gone, along with the one-unit package kambi_glwindow_navigated. I never liked these units, they were just handy shortcuts for simple demo programs. All normal programs should declare and explicitly create TGLWindow instance themselves, this is just 2 more lines of code but gives you better understanding what TGLWindow is and is more clean IMO.
      <li>Also, new kambi_vrml_game_engine*.tar.gz archives will contain offline HTML docs by pasdoc (the same ones <a href="http://vrmlengine.sourceforge.net/apidoc/html/index.html">as available online</a>).
    </ol>
    <!-- p>Please note that because of this change, pretty much everything inside <tt>kambi_vrml_game_engine</tt> is now, well, somewhere else. E.g. some URLs here and there may temporarily not work. Of course, please submit any observed problems, so that I may fix every page to be perfect. -->
  </li>
</ol>
'),

    array('title' => 'News - LGPL, SSAO demos, White Dune, more',
          'year' => 2009,
          'month' => 10,
          'day' => 30,
          'guid' => '2009-10-30',
          'short_description' => '',
          'description' =>

vrmlengine_thumbs(array(
  array('filename' => 'ssao_barna29_on.png' , 'titlealt' => 'Barna29 (with SSAO)'),
  array('filename' => 'ssao_barna29_off.png', 'titlealt' => 'Barna29 (without SSAO)'),
  array('filename' => 'ssao_stairs_on.png' , 'titlealt' => 'Stairs (with SSAO)'),
  array('filename' => 'ssao_stairs_off.png', 'titlealt' => 'Stairs (without SSAO)'),
), 2) . '

<ul>
  <li><p><a href="http://vrmlengine.sourceforge.net/kambi_vrml_game_engine.php#section_license">The core of our engine is now available under the GNU Lesser General Public License (with "static linking exception")</a>. Basically, this allows using the engine in closed-source programs, as long as you keep open your improvements to the engine.</p>

    <p>I had a long thought before this decision, always and still being a free software fanatic :) <!-- The text <i>In the future I may change the license to more liberal than GNU GPL &mdash; most probably to modified LGPL</i> was present in the "license" section since a few years. --> I wrote a short summary of my thoughts, <span ' . (HTML_VALIDATION ? '' : 'tabindex="0"') . ' class="js_link" onclick="kambi_toggle_display(\'lgpl-thoughts\')">click here to read it</span> (hidden by default, as may be boring to most people).</p>

    <ol id="lgpl-thoughts" style="display: none">
      <li><p>The initial insight is that with strict GPL, potential proprietary users of the engine wouldn\'t "open" their product just to use our engine. Instead they would move their interest elsewhere. We\'re <a href="http://openvrml.org/">not</a> <a href="http://freewrl.sourceforge.net/">the only</a> free/open VRML/X3D engine out there, neither we\'re the <a href="http://www.ogre3d.org/">the</a> <a href="http://irrlicht.sourceforge.net/">only</a> free/open 3d/game engine out there, and everyone else is available under LGPL (or even more permissible licenses).</p></li>

      <li><p>The common answer to above argument is that "popularity of the engine is not all that matters". LGPL is, ultimately, a permission to make closed-source software.</p>

        <p>The counter-thought to this is that LGPL still protects the freedom of my engine. You still have to share modifications to the engine, so it\'s not like properietary software can get all the benefits in some "unfair" way.</p></li>
    </ol>
  </li>

  <li><p>Victor Amat implemented demos of <a href="http://en.wikipedia.org/wiki/Screen_Space_Ambient_Occlusion">Screen Space Ambient Occlusion</a> using our <tt>GeneratedShadowMap</tt>. The complete examples, with shaders, are available inside our <a href="https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/kambi_vrml_test_suite/x3d/shadow_maps/">x3d/shadow_maps/ directory in kambi_vrml_test_suite (SVN only right now)</a>. Many thanks!</p>

    <p>Be sure to test these examples with view3dscene from <a href="http://michalis.ii.uni.wroc.pl/~michalis/vrmlengine-snapshots/">nightly builds</a>, as various problems reported by Victor (related to generating shadow maps) were fixed along the way.</p>

    <p>Some demo screenshots are on the right. They show the same view with/and without SSAO. (The comparison is somewhat unfair, as "without SSAO" versions just have GLSL shaders turned off. But the point is that they don\'t have smooth shadows (occlusion)).</p></li>

  <li><p>New <a href="http://vrml.cip.ica.uni-stuttgart.de/dune/">White Dune</a> release supports all <a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php">VRML/X3D extensions</a> of our engine. Thanks go to Joerg "MUFTI" Scheurich.</p></li>

  <li><p>Documentation of our "VRML / X3D implementation status" was refactored, each X3D component has now separate page with support details. This should make it easier to read and find needed things. <a href="http://michalis.ii.uni.wroc.pl/~michalis/vrmlengine-snapshots/docs/vrml_implementation_status.html">See the SVN documentation here</a>.

  <!-- Previous documentation (just one long page with stream of information) was fine a long time ago, when so little of VRML/X3D standards was implemented that it was sensible to mention only things that are actually working. Right now it makes more sense to focus on mentioning things that are missing :) -->
  </p></li>

  <li><p>Also, I noticed today that our <a href="http://michalis.ii.uni.wroc.pl/~michalis/vrmlengine-snapshots/">nightly builds</a> were down for the last 3 weeks. Sorry about that, fixed now.</p></li>
</ul>'),

    array('title' => 'Development news - more NURBS: interpolators, VRML 97 compatibility',
          'year' => 2009,
          'month' => 9,
          'day' => 7,
          'guid' => '2009-09-07',
          'short_description' => '',
          'description' =>
vrmlengine_thumbs(array(
  array('filename' => 'nurbs_curve_interpolators.png', 'titlealt' => 'Animating along the NURBS curve (NurbsPositionInterpolator and NurbsOrientationInterpolator)'),
  array('filename' => 'nurbs_surface_interpolator.png', 'titlealt' => 'Animating along the NURBS surface (NurbsSurfaceInterpolator)'),
)) . '
<p>Implementation of NURBS is progressing very nicely. In addition to previously announced nodes (rendered curves and surfaces: <tt>NurbsPatchSurface</tt> and <tt>NurbsCurve</tt>), we now also handle X3D NURBS interpolators: <tt>NurbsPositionInterpolator</tt>, <tt>NurbsSurfaceInterpolator</tt>, <tt>NurbsOrientationInterpolator</tt>. Using them you can animate movement of objects and viewpoints along the NURBS curves and surfaces.</p>

<p>Also basic VRML 97 NURBS nodes are implemented, for compatibility.</p>

<p><a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/vrml_implementation_nurbs.html">Up-to-date documentation about supported NURBS nodes is here.</a> Some demo scenes are inside kambi_vrml_test_suite in SVN, see e.g. <a href="https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/kambi_vrml_test_suite/x3d/nurbs_curve_interpolators.x3dv">nurbs_curve_interpolators.x3dv</a> and <a href="https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/kambi_vrml_test_suite/x3d/nurbs_surface_interpolator.x3dv">nurbs_surface_interpolator.x3dv</a>.</p>

<p>You can try the new features by using the <a href="http://michalis.ii.uni.wroc.pl/~michalis/vrmlengine-snapshots/">nightly builds</a> of <tt>view3dscene</tt>. Or, of course, you can wait for the next stable view3dscene 3.5 release &mdash; later this month.</p>
'),

    array('title' => 'Development news - NURBS basics',
          'year' => 2009,
          'month' => 9,
          'day' => 5,
          'guid' => '2009-09-05',
          'short_description' => '',
          'description' =>
vrmlengine_thumbs(array(
  array('filename' => 'nurbs_lantern.png', 'titlealt' => 'Lantern composed from NURBS patches (from web3d.org examples)'),
)) . '
<p>Basic support for X3D NURBS is implemented. <tt>NurbsPatchSurface</tt> and <tt>NurbsCurve</tt> nodes are handled following X3D specification.</p>

<p>As a background info: the core of our NURBS implementation (<a href="https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/kambi_vrml_game_engine/src/3d/nurbs.pas">nurbs unit</a>) is adapted from the <a href="http://vrml.cip.ica.uni-stuttgart.de/dune/">White_dune</a> source code. (Licensed on GPL &gt;= 2, just like our engine, so no problem here.)</p>

<p>For the next engine release, this NURBS support will be extended. I would like to cover X3D NURBS component up to level 2 and also implement most important VRML 97 NURBS nodes for compatibility (they are similar but a little incompatible to X3D ones).</p>

<p>For now, you can try the new features by using the <a href="http://michalis.ii.uni.wroc.pl/~michalis/vrmlengine-snapshots/">nightly builds</a> of <tt>view3dscene</tt>.</p>
'),

    array('title' => 'view3dscene 3.4 release - advanced texturing',
          'year' => 2009,
          'month' => 8,
          'day' => 26,
          'guid' => '2009-08-26',
          'short_description' => '',
          'description' =>
vrmlengine_thumbs(array(
  array('filename' => 'trees_river_shadow_maps.png', 'titlealt' => 'Shadow maps'),
  array('filename' => 'water_reflections.png', 'titlealt' => 'Water reflections by optimized GeneratedCubeMapTexture'),
  array('filename' => 'cubemap_teapot.png', 'titlealt' => 'Teapot with cube map reflections'),
)) . '

<p>' . news_a_href_page('view3dscene 3.4', 'view3dscene') . ' is released! The codename of this release should be <i>"Everything you wanted to know about textures"</i>, as most of the new features deal with X3D advanced texturing nodes.
<!-- ' . news_a_href_page('See recent news archive', 'news') . '  -->
</p>

<ul>
  <li><p>All X3D multi-texturing nodes implemented. See also <a href="http://vrmlengine.sourceforge.net/vrml_implementation_texturing.php#section_multi_texturing_clarifications">clarifications how MultiTexture.mode/source fields work and how to separate them for rgb and alpha channel</a>.</p></li>

  <li><p>All X3D cube map nodes implemented. This includes <tt>GeneratedCubeMapTexture</tt>, very useful to make mirrors, especially with the help of <a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_tex_coord_worldspace">WORLDSPACEREFLECTIONVECTOR extensions</a>.</p></li>

  <li><p>All X3D 3D texture nodes implemented.</p></li>

  <li><p>DDS (DirectDraw Surface) format is supported, for all texture types (2D, 3D in <tt>ImageTexture3D</tt>, cube map in <tt>ImageCubeMapTexture</tt>). S3TC compression, explicit mipmaps are all supported, <a href="http://vrmlengine.sourceforge.net/vrml_implementation_texturing.php#section_dds">more details here</a>. New ' . news_a_href_page('glViewImage 1.3.0', 'glviewimage') . ' supports reading, writing and even limited editing of DDS images.<!-- Many other usability fixes were done to <tt>glViewImage</tt> along the road.--></p></li>

  <li><p><a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_rendered_texture">RenderedTexture</a> node is implemented: a texture rendered from a specified viewpoint.</p></li>

  <li><p>Passing to GLSL shaders various uniform value types is implemented. This includes vectors, matrices and many more. <a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_viewpoint_camera_matrix">Viewpoint.camera*Matrix</a> may be very useful to feed to shaders. You can also <a href="http://vrmlengine.sourceforge.net/vrml_implementation_shaders.php#glsl_passing_uniform_textures">pass texture nodes to GLSL shader uniforms, following X3D specification</a>.</p></li>

  <li><p>New extensions to easily make <a href="http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_shadow_maps">projective texturing and shadow maps</a> within your VRML/X3D worlds.</p></li>

  <li><p>Anisotropic texture filtering (by standard X3D <tt>TextureProperties.anisotropicDegree</tt>).</p></li>

  <li><p><i>Hardware occlusion query</i> may be activated for rendering, this can speed browsing large scenes enormously. Try it by menu options <i>View -&gt; ... Occlusion Query</i>.</p></li>
</ul>

<!-- teaser -->

<ul>
  <li><p>When using single texturing, you can set environment mode to replace <a href="http://vrmlengine.sourceforge.net/vrml_implementation_status.php#default_texture_mode_modulate">(default is modulate)</a>.</p></li>

  <li><p><a href="http://vrmlengine.sourceforge.net/kambi_script.php">KambiScript</a> functions to operate on string characters: <tt>"character_from_code"</tt>, overloaded <tt>"array_set", "array_get", "array_get_count", "array_set_count"</tt> for strings.</li>

  <li><p>As usual, along with view3dscene release, we also release <a href="http://vrmlengine.sourceforge.net/kambi_vrml_game_engine.php">accompanying Kambi VRML engine (version 1.8.0)</a> for developers. Released binaries are compiled with FPC 2.2.4, sources can also be compiled with FPC from trunk (tested on 2009-08-21). I also provide binaries for Linux/x86_64 (not only 32-bit Linux/i386), as I see a demand for it.</li>
</ul>'
    ),

    array('title' => 'News from SVN - RenderedTexture node, Viewpoint.camera*Matrix events, and more',
          'year' => 2009,
          'month' => 8,
          'day' => 13,
          'guid' => '2009-08-13',
          'short_description' => '',
          'description' =>
vrmlengine_thumbs(array(
  array('filename' => 'rendered_texture.png', 'titlealt' => 'RenderedTexture demo'),
)) . '
<p>New features in SVN:</p>

<ul>
  <li><a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/kambi_vrml_extensions.html#section_ext_rendered_texture">RenderedTexture</a> node is implemented: a texture rendered from a specified viewpoint. Useful for many effects. The most straightforward use would be to make a "security camera" or a "portal", through which a player can peek what happens at the other place in 3D world. (<a href="http://vrmlengine.sourceforge.net/miscella/rendered_texture_one_file.x3dv">Simple example</a>).</li>
  <li><a href="http://michalis.ii.uni.wroc.pl/~michalis/vrmlengine-snapshots/docs/kambi_vrml_extensions.html#section_ext_viewpoint_camera_matrix">Viewpoint.camera*Matrix</a> output events are implemented, very useful for shaders.</li>
  <li><a href="http://michalis.ii.uni.wroc.pl/~michalis/vrmlengine-snapshots/docs/kambi_vrml_extensions.html#section_ext_tex_coord_worldspace">WORLDSPACEREFLECTIONVECTOR, WORLDSPACENORMAL extensions</a> are documented.</li>
  <li>We have a <a href="http://michalis.ii.uni.wroc.pl/~michalis/vrmlengine-snapshots/docs/vrml_implementation_texturing.php#section_multi_texturing">clear and precise specification how MultiTexture.mode/source fields work and how to separate them for rgb and alpha channel</a>.</li>
  <li>Texture handling code was refactored last week: we have much cleaner implementation now, various bump mapping fixes were done by the way, and all texture generating nodes use now OpenGL framebuffer (possibly faster, and texture dimensions no longer limited by window size).</li>
  <li><tt>Examine</tt> mode improved, to be more feature-rich like <tt>Walk</tt> mode: works nicely with <tt>LOD</tt> and <tt>ProximitySensor</tt> nodes, you can click on <tt>TouchSensor</tt> and such in <tt>Examine</tt> mode, you can initiate ray-tracer in view3dscene from <tt>Examine</tt> mode.</li>
</ul>

<p>Still no official release, but view3dscene 3.4 should be released Really Soon :) For now you can try new features by using the <a href="http://michalis.ii.uni.wroc.pl/~michalis/vrmlengine-snapshots/">nightly builds</a>.</p>
'),


    array('title' => 'News from SVN - 3D textures, shadow maps, hardware occlusion query and more',
          'year' => 2009,
          'month' => 5,
          'day' => 5,
          'guid' => '2009-05-05',
          'short_description' => '',
          'description' =>
vrmlengine_thumbs(array(
  array('filename' => 'trees_river_shadow_maps.png', 'titlealt' => 'Shadow maps'),
  array('filename' => 'tex3d_smoke.png', 'titlealt' => 'Fog from 3D noise'),
  array('filename' => 'anisotropic_demo.png', 'titlealt' => 'Demo how anisotropic filtering helps'),
  array('filename' => 'oq_demo.png', 'titlealt' => 'Occlusion query optimizing city view'),
  array('filename' => 'water_reflections.png', 'titlealt' => 'Water reflections by optimized GeneratedCubeMapTexture'),
), 2) . '

<p>New features implemented last month in our engine:</p>

<ul>
  <li>3D textures (full support for X3D <tt>Texturing3D</tt> component).
    In particular, <tt>ImageTexture3D</tt> supports 3D textures in DDS format.</li>
  <li>New extensions to easily make
    <a href="http://michalis.ii.uni.wroc.pl/vrmlengine-snapshots/docs/kambi_vrml_extensions.html#section_ext_shadow_maps">projective
    texturing and shadow maps</a> within your VRML/X3D worlds.</li>
  <li>Anisotropic texture filtering (by standard X3D <tt>TextureProperties.anisotropicDegree</tt>
    field).</li>
  <li><i>Hardware occlusion query</i> may be activated for rendering,
    this can speed browsing large
    scenes enormously. Implemented both the <a href="http://http.developer.nvidia.com/GPUGems/gpugems_ch29.html">basic method (see GPU Gems 1, Chapter 29)</a>
    and more involved algorithm <a href="http://http.developer.nvidia.com/GPUGems2/gpugems2_chapter06.html">Coherent Hierarchical Culling (see GPU Gems 2, Chapter 6)</a>.
    view3dscene has menu options (<i>View -&gt; ... Occlusion Query</i>) to try it all.
    </li>
  <li>And many other things: fixes and optimizations for <tt>GeneratedCubeMapTexture</tt>,
    glViewImage improvements (you no longer have to open files from command-line),
    S3TC compressed textures (from DDS; usable as textures, also viewable in glViewImage),
    sorting transparent shapes (for better blending),
    exit shortcut for view3dscene is Ctrl+W (escape was too error-prone).
</ul>

<p>For the brave: you can test these features already by trying the
<a href="http://michalis.ii.uni.wroc.pl/~michalis/vrmlengine-snapshots/">nightly builds</a>
(or grabbing source code from SVN and compiling yourself, of course).</p>
'),

    array('title' => 'News from SVN &mdash; X3D multi-texturing, cube maps and more',
          'year' => 2009,
          'month' => 4,
          'day' => 10,
          'guid' => '2009-04-10',
          'short_description' => '',
          'description' =>

vrmlengine_thumbs(array(
  array('filename' => 'cubemap_teapot.png', 'titlealt' => 'Teapot with cube map reflections'),
)) . '

<p>Hi! I didn\'t post a message here since some time, but rest assured that the development of next engine version continues :) Things already implemented in the SVN include:

<ul>
  <li>X3D multi-texturing.</li>
  <li>X3D cube map nodes.</li>
  <li>This also means that DDS file format (for <tt>ImageCubeMapTexture</tt>) is implemented (both reading and writing, you can even use glViewImage as a simple DDS editor).</li>
  <li>This includes generating textures on the fly (for <tt>GeneratedCubeMapTexture</tt>).</li>
  <li>As extensions, I added texture generation modes "<tt>WORLDSPACEREFLECTIONVECTOR</tt>" and "<tt>WORLDSPACENORMAL</tt>" (analogous to X3D standard modes in CAMERA space) to make simulating real reflections trivial.</li>
  <li>There is also quite cool new feature in view3dscene to catch a "screenshot" of 3D world around you as a cube map (DDS, or six separate normal images).</li>
  <li>Passing almost all possible VRML types to GLSL shaders is implemented.</li>
  <li>..and a lot of other cool features are already implemented :)</li>
</ul>

<p>The plan for the next release (view3dscene 3.4, engine 1.8) is to polish implementation of all above (yes, there are some known problems, also <tt>GeneratedCubeMapTexture</tt> implementation is severely unoptimal now), and add related texturing and GLSL features:</p>

<ul>
  <li>3D texturing (that\'s easy since we already have DDS).</li>
  <li>Basic implementation of <a href="http://www.instantreality.org/documentation/nodetype/RenderedTexture/"><tt>RenderedTexture</tt></a> from InstantReality (that\'s easy since it\'s internally simpler than <tt>GeneratedCubeMapTexture</tt>).</li>
  <li>Finish GLSL stuff by supporting X3D attributes nodes.</li>
</ul>

<p>For the impatient: <a href="http://michalis.ii.uni.wroc.pl/~michalis/vrmlengine-snapshots/">nightly builds of vrmlengine binaries (including view3dscene) are available.</a> They are build automatically every night using current SVN code. Use at your own risk, of course &mdash; they <i>do</i> contain some known bugs. For now, they are made for Linux and Windows (32-bit).</p>
'),

    array('title' => 'Dynamic Ambient Occlusion, Shadow Fields demos in the engine sources',
          'year' => 2009,
          'month' => 1,
          'day' => 24,
          'guid' => '2009-01-24',
          'short_description' => '',
          'description' =>

vrmlengine_thumbs(array(
  array('filename' => 'dyn_ao_chinchilla.png', 'titlealt' => 'Chinchilla with Dynamic Ambient Occlusion'),
  array('filename' => 'dyn_ao_chinchilla_elements.png', 'titlealt' => 'Chinchilla elements used for Dynamic Ambient Occlusion'),
  array('filename' => 'dyn_ao_peach.png', 'titlealt' => 'Peach with Dynamic Ambient Occlusion'),
  array('filename' => 'sf_1.png', 'titlealt' => 'Shadow Fields screenshot 1'),
  array('filename' => 'sf_2.png', 'titlealt' => 'Shadow Fields screenshot 2'),
  array('filename' => 'sf_3.png', 'titlealt' => 'Shadow Fields screenshot 3'),
), 2) . '

<p>This week I implemented a demo of <a href="http://http.developer.nvidia.com/GPUGems2/gpugems2_chapter14.html">Dynamic Ambient Occlusion</a> using our engine.</p>

<p>In related news, last month I also implemented a demo of <a href="http://www.kunzhou.net/#shadow-field">Shadow Fields</a>. (I forgot to brag about it earlier, so I\'m doing it now :) ).</p>

<p>An extremely short summary: both techniques strive to make low-frequency soft shadows (in other words, the kind of shadows you usually see in the Real World) in dynamic 3D environment.</p>

<p>For now, they are just implemented as demos, and are not easily available for non-programmers. You have to actually get the source and compile some example programs to try out this stuff. (Although I think I\'ll make at least dynamic ambient occlusion available as an easy option inside view3dscene in the future.) The full source code, and example models, are available in SVN, naturally. Simple instructions:</p>

<pre>
$ svn checkout https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/kambi_vrml_game_engine/

$ cd kambi_vrml_game_engine/examples/vrml/shadow_fields
$ ./shadow_fields_compile.sh
$ ./shadow_fields

$ cd ../dynamic_ambient_occlusion
$ ./dynamic_ambient_occlusion_compile.sh
$ ./dynamic_ambient_occlusion models/peach.wrl.gz
</pre>

<p>There are more sample models in the <tt>models</tt> subdirectories,
and you can test both demos with your own models.
Both techniques require highly-tesselated
models to make shadows look nice. Shadow fields require preprocessing
by included <tt>precompute_shadow_field</tt> program. Dynamic ambient
occlusion doesn\'t require any preprocessing, but it requires really good
GPU (it does ~thousands of texture fetches per pixel in GLSL fragment shader).
You can find a lot of notes and links in the README files inside the
source directories.</p>

<p>Have fun!</p>'),

    array('title' => 'view3dscene 3.3, engine 1.7 release: LOD, Collision.proxy, and more',
          'year' => 2009,
          'month' => 1,
          'day' => 3,
          'guid' => '2009-01-03',
          'short_description' => '',
          'description' => "

<p>" . news_a_href_page('view3dscene 3.3', 'view3dscene') . " is released,
just a mixture of various new features, optimizations and fixes.
Traditionally, " . news_a_href_page('underlying
Kambi VRML game engine 1.7.0', 'kambi_vrml_game_engine') . " is released along.
Changes:</p>

" .
vrmlengine_thumbs(array(
  array('filename' => 'apple_lods.png', 'titlealt' => 'Apple model with various levels of detail'),
)) . "

<ul>
  <li><b>LOD (level-of-detail)</b> node proper handling.</li>

  <li><b>Collision.proxy</b> handling (very handy, allows you to make non-collidable but visible geometry, or approximate complex geometry with simpler for collision detection).</li>

  <li><a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_octree_properties\">KambiOctreeProperties, an extensions to specify octree limits for your scene</a>. <a href=\"" . CURRENT_URL . "vrml_engine_doc/output/xsl/html/section.octrees_dynamic.html\">Section \"Octrees for dynamic worlds\" added to the documentation</a>, to explain how octree works since 1.6.0 version. The shape octree was speed up by mailboxes.</li>

  <li>Various workarounds for <a href=\"http://mesa3d.org/\">Mesa</a> bugs (in particular on Intel GPUs) and Mesa detection improved. This should significantly improve stability for Unix users with cheaper graphic cards. Because of this, also " . news_a_href_page('castle 0.8.3', 'castle') . " and " . news_a_href_page('glcaps 1.1.4', 'glcaps') . " are released, to get these fixes too.</li>

  <li>Various frustum culling optimizations.</li>

  <li>Small improvements in the view3dscene interface: blend status text, a shapes count fix, and keeping the selected triangle when transforming shape.</li>

  <li>The path tracer honors VRML &gt;= 2.0 materials, and <a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_material_phong_brdf_fields\">VRML &gt;= 2.0 materials have the physical fields</a>. Because of this, also " . news_a_href_page('rayhunter 1.3.0', 'rayhunter') . " is released.</li>
</ul>

<p><a href=\"http://www.archlinux.org/\">Arch Linux</a> users may now install view3dscene from SVN easily by <a href=\"http://aur.archlinux.org/packages.php?ID=22782\">view3dscene Arch Linux package</a>. Thanks to Antonio Bonifati!
"),

    array('title' => 'view3dscene 3.2, engine 1.6 release: collisions in dynamic worlds',
          'year' => 2008,
          'month' => 12,
          'day' => 18,
          'guid' => '2008-12-18',
          'short_description' => '',
          'description' => "

<p>" . news_a_href_page('view3dscene 3.2', 'view3dscene') . " is released,
with a lot of improvements and optimizations for dynamic VRML/X3D worlds.
As usual, this is accompanied by " . news_a_href_page('underlying
Kambi VRML game engine 1.6.0', 'kambi_vrml_game_engine') . " release.
Major changes:

<ul>
  <li><p>Our spatial data structure has undergone serious
    rework, which in English means that <b>your whole scene
    can be now much more dynamic</b>. Everything can move and transform,
    and things will work smoothly
    and fast. All collision detection routines will \"see\"
    the most current scene state, which means that you
    can e.g. click on moving targets, and you will fall down if a hole
    opens under your feet, and generally you can interact with every
    dynamic part of your scene without problems.</p>

    " . (!HTML_VALIDATION ?
    '<table align="right"><tr><td>
       <object width="300" height="243"><param name="movie" value="http://www.youtube.com/v/qtrSIisc6do"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/qtrSIisc6do" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="300" height="243"></embed></object>
     </td></tr></table>' : '')
    . "


    <p>I prepared a video showing a simple dynamic world written in X3D and played with view3dscene, see it on the right. The video is only a poor substitute for actually running and playing with this yourself, feeling the smoothness of all editing (the poor framerate of the video is only because of the capturing process...). So after downloading view3dscene, you're welcome to also download this <a href=\"https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/kambi_vrml_test_suite/x3d/dynamic_world.x3dv\">demo dynamic_world.x3dv</a> (or just grab it along with the rest of " . news_a_href_page('Kambi VRML test suite', 'kambi_vrml_test_suite') .
    ") and open it. It shows how you can edit the world by KambiScript, how changing transformations works fast, and how it all cooperates with collision detection &mdash; whatever scene you will build, your avatar will move honoring collision detection.

  <li><p>Changing <b><tt>Switch.whichChoice</tt> is greatly optimized</b>.
    Previously this was very costly operation, now it's instantaneous
    (almost no work is required).
    Many other optimizations were done for dynamic worlds, including
    <b>faster <tt>Transform</tt> traversing</b>, faster sensor's enabled switching,
    more reliable starting of time-dependent nodes and many more.

    <p>Thanks to <a href=\"http://www.de-panther.com/\">De-Panther</a> for
    pushing me to implement a lot of these features!


" .
vrmlengine_thumbs(array(
  array('filename' => 'shadows_dynamic_2.png', 'titlealt' => 'Dynamic shadows screenshot'),
))
. "

  <li>
    <p><b>Dynamic shadows support is greatly improved</b>, finally
    " . news_a_href_page('view3dscene', 'view3dscene') . " can render
    with shadows, honoring our <a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_shadows\">shadow's extensions</a>.
    We also have new <a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_shadow_caster\">shadowCaster</a> extension.
    Oh, and shadows rendering with
    transparent objects is fixed. Just try the file
    <tt>x3d/kambi_extensions/shadows_dynamic.x3dv</tt> from
    " . news_a_href_page('Kambi VRML test suite', 'kambi_vrml_test_suite') . "
    in new view3dscene.

    <p>view3dscene has now view mode <i>Fill Mode -&gt;
    Silhouette and Border Edges</i>, this is very handy for checking
    whether your models are correctly manifold (this is important for
    shadow volumes).

  <li><p><tt>ProximitySensor.orientation_changed</tt>,
    <tt>X3DSequencerNode</tt>,
    <tt>BooleanSequencer</tt>, <tt>IntegerSequencer</tt> implemented.

    <p><a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_alpha_channel_detection\">alphaChannel extension field</a> added to all texture nodes.

  <li><p>Bugfix for open file dialog under GTK 2.14 (like Ubuntu 8.10).
    Thanks to Graham Seed for reporting.
</ul>
"),

    array('title' => 'Precomputed Radiance Transfer using our engine',
          'year' => 2008,
          'month' => 11,
          'day' => 9,
          'guid' => '2008-11-09',
          'short_description' => '',
          'description' =>

vrmlengine_thumbs(array(
  array('filename' => 'chinchilla_normal.png', 'titlealt' => 'Normal OpenGL lighting'),
  array('filename' => 'chinchilla_simple_occlusion.png', 'titlealt' => 'Rendering with simple ambient occlusion'),
  array('filename' => 'chinchilla_diffuse_prt.png', 'titlealt' => 'Precomputed Radiance Transfer'),
))
. "

<p>I implemented a demo of <a href=\"http://en.wikipedia.org/wiki/Precomputed_Radiance_Transfer\">Precomputed Radiance Transfer</a> using our engine.</p>

<p>In a few words, this is a technique to make very cool self-shadowing by soft shadows under dynamic lighting. (Actually it's possible to go much further, see the papers about PRT <a href=\"https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/kambi_vrml_game_engine/examples/vrml/radiance_transfer/README\">linked from my README</a>). You can see the screenshots on the right: 1st shows normal OpenGL lighting (without PRT), 2nd shows the simple ambient occlusion per-vertex (this is, in some sense, a special case of PRT), and the 3rd screenshot shows PRT technique in all it's glory.</p>

<p>The full source code is available, naturally. Simple instructions:</p>

<pre>
$ svn checkout https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/kambi_vrml_game_engine/
$ cd kambi_vrml_game_engine/examples/vrml/radiance_transfer
$ ./radiance_transfer_compile.sh
$ ./radiance_transfer models/chinchilla_with_prt.wrl.gz
</pre>

<p>Inside that directory there are also other models ready to test with
PRT. There's also <tt>precompute_radiance_transfer</tt> to process
any 3D model (readable by my engine &mdash; VRML, X3D, 3DS, Wavefront,
Collada...) into a VRML model that can be displayed using
<tt>radiance_transfer</tt> with PRT effects. There's also <tt>show_sh</tt>
program to view 25 first <a href=\"http://en.wikipedia.org/wiki/Spherical_harmonics\">spherical harmonics</a> (this will be useful if you'll want to understand how PRT works :) )."),

    array('title' => 'view3dscene 3.1, engine 1.5 release: Scripting, VRML browser components, and more',
          'year' => 2008,
          'month' => 10,
          'day' => 15,
          'guid' => '2008-10-15',
          'short_description' => '',
          'description' =>
news_a_href_page('view3dscene 3.1.0', 'view3dscene') . " release,
along with " . news_a_href_page('underlying
Kambi VRML game engine 1.5.0', 'kambi_vrml_game_engine') . " release.
Most notable improvements are:

" .
vrmlengine_thumbs(array(
  array('filename' => 'kambi_script_ball_game.png', 'titlealt' => 'Simple game implemented in pure X3D with KambiScript'),
  array('filename' => 'kambi_script_particles.png', 'titlealt' => 'Particle engine programmed in pure X3D with KambiScript'),
  array('filename' => 'kambi_script_edit_texture.png', 'titlealt' => 'Texture editor (pure X3D with KambiScript)'),
))
. "

<ul>
  <li><p><b>Scripting in " . news_a_href_page('KambiScript language',
    'kambi_script') . "</b>. KambiScript is a simple scripting language,
    invented specially for our engine. It's powerful
    enough for many tasks, you can process all VRML data types
    with it (including vectors, matrices, arrays, images).</p>

    <p>Screenshots on the right show example uses of KambiScript.
    Endless possibilities are available now for VRML authors, you can
    write complete interactive 3D games and run them with view3dscene
    (or any other VRML browser using our engine).
    " . news_a_href_page('Kambi VRML test suite 2.3.0', 'kambi_vrml_test_suite') .
    " contains source VRML files with KambiScript tests (see <tt>x3d/kambi_extensions/kambi_script_*</tt>
    in there, like
    <a href=\"https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/kambi_vrml_test_suite/x3d/kambi_extensions/kambi_script_ball_game.x3dv\">kambi_script_ball_game.x3dv</a>
    or
    <a href=\"https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/kambi_vrml_test_suite/x3d/kambi_extensions/kambi_script_particles.x3dv\">kambi_script_particles.x3dv</a>),
    you can simply open them in view3dscene.</p></li>

  <li><p><b>Animating camera by animating Viewpoint position</b> (or it's transformation)
    works.</p></li>

  <li><p>Various <b>navigation improvements for scripted worlds</b>:
    <tt>NavigationInfo.type = \"NONE\"</tt> and
    <tt>NavigationInfo.speed = 0</tt> cases are supported.
    They are useful when you implement whole navigation yourself, by <tt>KeySensor</tt>
    and scripting.</p>

    <p>Also view3dscene key shortcuts changed, to allow easily avoiding
    collisions with keys that you handle through <tt>KeySensor</tt> and scripting.
    All menu shortcuts are now with <i>Ctrl</i> modifier
    (for example, previously you switched collision detection with <i>C</i>,
    now you have to press <i>Ctrl+C</i>).</li>

<!--
   Some minor improvements were made to
    to generate correct events on both key down and key up, with both TGLWindow
    and Lazarus component.

    simple nodes <tt>Circle2D</tt>,
    <tt>TextureTransformMatrix3D</tt>, <tt>TextureTransform3D</tt>,
    <tt>MultiTextureTransform</tt>.</p>
-->


    <!--
    Changing LineSet and PointSet_2 through VRML events fixed.
    KeySensor fixes:
    - send lowercase letters (for Lazarus component) when shift not pressed
    -->

  <li><p>For programmers using our engine, we have <b>VRML browser
    components</b>. Two flavors: <tt>TGLWindowVRMLBrowser</tt> (a descendant
    of our <tt>TGLWindow</tt>) and, for Lazarus LCL, <tt>TKamVRMLBrowser</tt>
    (a descendant of <tt>TOpenGLControl</tt>). Using them is trivial,
    just drop <tt>TKamVRMLBrowser</tt> on the form
    and call it's <tt>Load</tt> method &mdash; whole rendering and navigation
    will automatically work. Other Lazarus packages
    fixes were made, to make them more easily usable. Thanks to De-Panther for
    pushing me to implement this :)</p></li>

  <li><p><b><a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_script_compiled\">Script
    protocol \"<tt>compiled:</tt>\"</a></b> is implemented, to easily link
    VRML scripts with compiled-in (written in ObjectPascal) handlers.</p></li>

  <li><p>Other improvements: using quaternions for <tt>EXAMINE</tt> navigation,
    simple nodes <tt>Circle2D</tt>, <tt>TextureTransformMatrix3D</tt>,
    <tt>TextureTransform3D</tt>, <tt>MultiTextureTransform</tt>.

  <!--

    glplotter 1.2.2 and gen_function 1.0.3 released, to update
    kambiscript expressions handling inside.
  -->
</ul>

<p><i>Plans for the next release</i>: first of all octree updating problems
will be solved.
Right now they are a weak point of our engine &mdash; animating geometry
by VRML events is unnecessarily time-consuming when collision detection has to be up-to-date.
Also more rendering optimizations for animating by VRML events
will be done (optimize case when you often change <tt>Switch</tt>
choice, automatically detect when roSeparateShapeStates /
...NoTransform / roNone is suitable).</p>
"),

    array('title' => 'view3dscene 3.0 release: X3D, events, MovieTexture, and more',
          'year' => 2008,
          'month' => 9,
          'day' => 12,
          'guid' => '2008-09-12',
          'short_description' => '',
          'description' =>
"<p>I'm pleased to present the new, shiny
" . news_a_href_page('view3dscene 3.0', 'view3dscene') . " release,
with a lot of new <a href=\"http://www.web3d.org/\">VRML/X3D</a> features
implemented. Also " . news_a_href_page('underlying Kambi VRML game engine 1.4.0', 'kambi_vrml_game_engine') . "
is released and some other programs here get minor updates.</p>

<p>New features of the engine and view3dscene:</p>

" . vrmlengine_thumbs(array(
  array('filename' => 'deranged_house_final_0.png', 'titlealt' => 'ProximitySensor in action'),
  array('filename' => 'ikea_bead_toy.png', 'titlealt' => 'Animation (IkeaBeadToy model from www.web3d examples)'),
))
. "

<ul>
  <li><p><b>X3D support</b> (both XML and classic encoding).
    Our " . news_a_href_page('VRML implementation status', 'vrml_implementation_status') . "
    page has detailed information about supported features.</p></li>

  <li><p><b>Events mechanism</b> (routes, exposed events, sensors, interpolators etc.)
    is implemented. This allows you to define interactions and animations
    of 3D world within a single VRML/X3D file, as envisioned in
    the specifications.</p>

    <p>Four basic sensors are implemented now: <tt>TimeSensor</tt>,
    <tt>TouchSensor</tt>, <tt>KeySensor</tt> and <tt>ProximitySensor</tt>.
    Also <tt>Anchor</tt> is \"clickable\" now. For now you have to be in
    <tt>Walk</tt> mode with <i>Collision Checking</i>
    enabled to have picking (<tt>TouchSensor</tt>, <tt>Anchor</tt>) working.</p>

    <p>Linear interpolators are also implemented.
    Some \"event utilities\" nodes are implemented
    (including useful <a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_avalon\"><tt>Logger</tt>
    node from Avalon extensions</a>).
    Events to work with bindable nodes (Background, Fog and such) work.
    <a href=\"http://vrmlengine.sourceforge.net/vrml_implementation_shaders.php\">Routing
    events to GLSL shaders uniform variables works perfectly.</a>.
    Events to control behavior of <tt>Inline</tt> (and <tt>InlineLoadControl</tt>
    for VRML 97) work too.
    Prototypes and external prototypes also work 100% with events according
    to specification, so you can pass events to/from prototypes.
    New " . news_a_href_page('Kambi VRML test suite 2.2.0', 'kambi_vrml_test_suite') . "
    has some simple demos of our events implementation.</p></li>

  <li><p><b>MovieTexture</b> is handled, with very useful <a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_movie_from_image_sequence\">extension
    to load movie from a sequence of images (with possible alpha
    channel)</a>, this is great for pre-rendered animations of flames, smoke etc.
    Normal movie formats are also handled if <a href=\"http://ffmpeg.mplayerhq.hu/\">ffmpeg</a>
    is installed and available on \$PATH.</p></li>

  <li><p><b>Recording movies</b> from view3dscene is possible.
    This allows recording 3D animations to a movie file
    (with perfect quality, as opposed to using independent
    programs that capture OpenGL output).</p></li>

  <li>
" . vrmlengine_thumbs(array(
  array('filename' => 'view3dscene_thumbnailer_demo.png', 'titlealt' => '&quot;view3dscene&quot; as nautilus thumbnailer'),
))
. "
    <p><a href=\"http://www.gnome.org/\">GNOME</a> users will be happy to
    hear that view3dscene can be easily used as nautilus thumbnailer,
    so you can see thumbnails of your VRML / X3D and other 3D model files.</p></li>

  <li><p>Many other features, including
    <ul>
      <li><tt>Extrusion</tt> node handling,</li>
      <li><a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_blending\"><tt>BlendMode</tt> extension</a>,
      <li><a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_kambi_inline\"><tt>KambiInline</tt> extension
        to automatically replace nodes within inlined content</a>,</li>
      <li><a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_time_origin_at_load\">extension
        to force VRML time-origin to start at loading time</a>, because
        <a href=\"http://vrmlengine.sourceforge.net/vrml_time_origin_considered_uncomfortable.php\">standard
        VRML time origin is uncomfortable in my opinion,</a></li>
      <li>new X3D Indexed Triangles/Quads primitives (thanks to completely
        reorganized mesh renderer code),</li>
      <li>HAnim nodes support.</li>
    </ul>
  </li>

  <li><p>" . news_a_href_page('"The Castle" 0.8.2', 'castle') . " release also deserves
    mention, as it fixes <b>support for OpenAL Soft</b> implementation
    (available on newer Linux distros).
    It also allows creators of future levels and creatures to use X3D,
    animating with standard VRML/X3D interpolators,
    defining level interactions by VRML events etc.</li>
</ul>

<p>Have fun! You may also enjoy reading <a href=\"http://news.hiperia3d.com/2008/09/interview-michalis-kamburelis-developer.html\">an
interview with me about our VRML engine on Hiperia3D News (regards go to
Jordi R. Cardona!)</a>.</p>"),

    array('title' => 'VRML / X3D events and routes implemented',
          'year' => 2008,
          'month' => 8,
          'day' => 15,
          'guid' => '2008-08-15',
          'short_description' => '',
          'description' =>

vrmlengine_thumbs(array(
  array('filename' => 'laetitia_sprints_demo.png', 'titlealt' => 'Laetitia Sprints by X3D TimeSensor + CoordinateInterpolator', 'linktarget' => CURRENT_URL .  'movies/laetitia_sprints.avi'),
))
. "

<p>An extremely important feature of VRML / X3D is finally implemented:
routes and events mechanism works. This means that you can express animations and interactions
within single VRML / X3D file, like envisioned in the specifications.</p>

<p>We have 2 sensor nodes working already (<tt>TimeSensor</tt>
and <tt>KeySensor</tt>), 7 linear interpolator nodes, and 5 event utilities
nodes (including <a href=\"http://instant-reality.com/documentation/nodetype/Logger/\">Avalon
<tt>Logger</tt> node</a>, a useful debugger for events).
All exposed fields of other nodes also work, obviously.
This is all available only in SVN for now. When I get back from vacation
(at the end of August) this work will be continued (many other sensors
are easy to implement now, and some existing code should be cleaned and
optimized) and it will all be released as <i>view3dscene 3.0</i>.</p>

<p>As a demo, see the 6-second movie on the right. It shows
animation in X3D done by routing
<tt>TimeSensor</tt> to <tt>CoordinateInterpolator</tt> to <tt>IndexedFaceSet</tt>.
The model is <a href=\"http://www.web3d.org/x3d/content/examples/Basic/StudentProjects/\">\"Laetitia Sprints\" from
web3d.org examples</a>.</p>"),

    array('title' => 'News - white_dune, X3D, movie textures, engine icon',
          'year' => 2008,
          'month' => 7,
          'day' => 15,
          'guid' => '2008-07-15',
          'short_description' => '',
          'description' =>
'Various exciting news about development of our engine:

<ul>
  <li><p><a href="http://vrml.cip.ica.uni-stuttgart.de/dune/">White dune</a>,
    free software VRML 97 modeler,
    can export normal VRML animations
    (expressed in terms of VRML interpolators) to our ' .
    news_a_href_page('Kanim (Kambi animations) file format', 'kanim_format') .
    ' and it supports our ' .
    news_a_href_page('extension nodes and fields', 'kambi_vrml_extensions') .
    ' (run with <tt>-kambi</tt> command-line option, or use <i>"Start next time
    with kambi support"</i> menu item). Thousand thanks for
    Joerg "MUFTI" Scheurich!</p>
  </li>

  <li><p>Among the many new features already implemented in SVN are:</p>

    <ul>
      <li><p>Reading X3D files, with all 40 X3D components,
        in both XML and classic VRML encodings,
        is implemented.</p>

        <p>Besides all features from VRML 2.0, many X3D-specific
        features are already supported, like
        geometric primitives <tt>[Indexed][Triangle/Quad][Fan/Strip]Set</tt> (8 nodes total).
        Rendering internals were reorganized into much smarter hierarchy, to handle
        these new X3D nodes as well as <tt>IndexedFaceSet</tt> and other VRML 97 and 1.0
        nodes implemented since a long time.</p>
      </li>

      <li><p><tt>Extrusion</tt> node handling.</p></li>

      <li><p>New extensions, like <tt>BlendMode</tt> node (a subset of
        <a href="http://www.instantreality.org/documentation/nodetype/BlendMode/">Avalon BlendMode node</a>)
        and <tt>KambiInline</tt> (an Inline that can somewhat
        process the inlined content).</p></li>

      <li>

        ' . (!HTML_VALIDATION ?
        '<table align="right"><tr><td>
           <object width="200" height="167"><param name="movie" value="http://www.youtube.com/v/V-EJvVbi1DQ"> </param> <embed src="http://www.youtube.com/v/V-EJvVbi1DQ" type="application/x-shockwave-flash" width="200" height="167"> </embed> </object>
         </td></tr></table>' : '')
        . '

        <p>Texture department:
        Textures with full alpha channel are now nicely rendered with blending
        (and textures will simple alpha channel are still detected and rendered
        faster by alpha_test). Moreover, <tt>MovieTexture</tt> node is now
        handled (movie can be read from image sequences, like <tt>image%d.png</tt>,
        and from normal movie formats thanks to <a href="http://ffmpeg.mplayerhq.hu/">ffmpeg</a>).
        As a demo, see the flames animation on the right.
        (You can also <a href="http://vrmlengine.sourceforge.net/movies/fireplace_demo.avi">download
        AVI version with perfect quality</a>.)</p>

      <li><p>Flames movie above was not only played in our ' .
        news_a_href_page('view3dscene', 'view3dscene') . ', it was also
        recorded directly by view3dscene. That\'s right: Screenshot options
        were much improved, it\'s now possible to capture animation
        as a movie file (with perfect quality, as opposed to using independent
        programs that capture OpenGL output).</p>

' . vrmlengine_thumbs(array(
  array('filename' => 'view3dscene_thumbnailer_demo.png', 'titlealt' => '&quot;view3dscene&quot; as nautilus thumbnailer'),
))
. '
        <p><a href="http://www.gnome.org/">GNOME</a> users will be happy to
        hear that view3dscene can be easily used as nautilus thumbnailer,
        so you can see thumbnails of your VRML / X3D and other 3D model files
        (see the screenshot).
        </p>
      </li>
    </ul>
  </li>

  <li><p>We have an icon for our engine and view3dscene.
    Next view3dscene release will be nicely integrated with GNOME
    (and other desktops that support relevant freedesktop specs).
    You can already appreciate engine icon at the top corner of our main page.
    Thanks to Kasia Obrycka for icon improvements!</p>
</ul>'),

    array('title' => 'Demo movies',
          'year' => 2008,
          'month' => 5,
          'day' => 9,
          'guid' => '2008-05-09',
          'short_description' => '',
          'description' =>
"I present " . news_a_href_page('three demo movies', 'movies') . "
showing off my engine. Feast your eyes on!

<p>In related news, development of the engine goes on.
Some of the latest improvements include
<ul>
  <li><i>X3D XML handling</i>. Next release
    will include support for X3D (both XML and classic encoding) for all
    programs.
  <li><i>File filters in open/save dialogs</i>.
    In GTK 2 (by GtkFileChooserDialog) and Windows (WinAPI) backends.
  <li>Passing kambi_time to GLSL shaders, allowing <i>shaders to perform
    various beautiful animations</i>.
</ul>"),

    array('title' => 'Engine 1.3.1 release (Lazarus packages fixed)',
          'year' => 2008,
          'month' => 2,
          'day' => 25,
          'guid' => '2008-02-25',
          'short_description' => '',
          'description' =>
"Released " . news_a_href_page('engine version 1.3.1', 'kambi_vrml_game_engine') . ":
fixed Lazarus packages compilation, for developers that want to use our
engine with Lazarus."),

    array('title' => 'Engine 1.3.0 release, view3dscene 2.4.0, castle 0.8.1, many other releases',
          'year' => 2008,
          'month' => 2,
          'day' => 19,
          'guid' => '2008-02-19',
          'short_description' =>
"<p>Many long-awaited graphic features implemented in our engine.
Released " . news_a_href_page('engine version 1.3.0', 'kambi_vrml_game_engine') . ",
" . news_a_href_page('view3dscene 2.4.0', 'view3dscene') . " and
" . news_a_href_page('castle 0.8.1', 'castle') . ".
Below is only a shortcut of the most important changes
(see " . news_a_href_page('changes_log', 'news') . " for a full list of changes) :</p>

<ul>
  <li><a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#ext_bump_mapping\">Bump
    mapping</a>. Various bump mapping methods are implemented,
    the most advanced being steep parallax mapping with self-shadowing.</li>

  <li>Shaders support, including <a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#ext_shaders\">specifying GLSL
    shaders in VRML</a>. Programmers may easily initialize
    GLSL and ARB assembly shaders.</li>

  <li>Anti-aliasing available in both "
    . news_a_href_page('view3dscene', 'view3dscene') . "
    and " . news_a_href_page('castle', 'castle') . ".</li>

  <li>Collada model format basic support (1.3.x and 1.4.x)
    added to the engine, you can also convert Collada files to VRML 2.0.</li>

  <li><i>Examine</i> mode allows to rotate and move the scene by mouse
    dragging.</li>

  <li><tt>--screenshot</tt> command-line option for
    " . news_a_href_page('view3dscene', 'view3dscene') . ",
    to take screenshots of the scene in batch mode.</li>

  <li>" . news_a_href_page('Our Blender VRML 97 exporter script', 'blender_stuff') . "
    improved: <i>set solid / set smooth / autosmooth / autosmooth degrees</i>
    settings from Blender are correctly exported to VRML.</li>
</ul>

<p>Other releases:
" . news_a_href_page('Kambi VRML test suite 2.1.0', 'kambi_vrml_test_suite') . "
has many new tests/demos for new features (bump mapping, GLSL,
Collada format). Also released most other programs,
to bring them up-to-date with current engine state.</p>
",
          'description' =>

"<p>Released " . news_a_href_page('engine version 1.3.0', 'kambi_vrml_game_engine') . ",
" . news_a_href_page('view3dscene 2.4.0', 'view3dscene') . " and
" . news_a_href_page('castle 0.8.1', 'castle') . ".
Many long-awaited graphic features implemented:</p>

<ul>
  <li><p><b>Bump mapping</b>: <a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#ext_bump_mapping\">VRML
    renderer allows bump mapping</a>. Various bump mapping methods
    are implemented (best method is auto-detected and used at runtime):
    dot by multitexturing (not normalized and normalized by cube map),
    dot by GLSL (optionally with parallax mapping, optionally with
    steep parallax mapping and self-shadowing).</p>

    <p>" . news_a_href_page('view3dscene', 'view3dscene') . " allows to easily
    turn on bump mapping, assuming model specifies normal maps.<br/>
    " . news_a_href_page('castle', 'castle') . " uses bump mapping, for now only
    on the \"fountain\" level.</p>

    <p><i>For programmers</i>: see also <tt>kambi_vrml_game_engine/examples/vrml/bump_mapping</tt> demo in engine sources,
    it demonstrates emboss, dot and all other bump mapping
    methods built in VRML engine. Also my notes about emboss and dot
    (by multitexturing) bump mapping methods may be interesting:
    see <a href=\"http://vrmlengine.svn.sourceforge.net/viewvc/*checkout*/vrmlengine/trunk/kambi_vrml_game_engine/examples/vrml/bump_mapping/README\">bump_mapping/README</a>.</p>
  </li>

  <li><p><b>GLSL shaders support:</b> engine supports easily using
    ARB vertex / fragment programs (assembly shaders) and&nbsp;GLSL.</p>

    <p>You can also <a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#ext_shaders\">directly
    specify GLSL shaders inside VRML file</a>, which is a great feature
    for VRML authors. Syntax of shaders in VRML follows X3D specification.</p>

    <p>" . news_a_href_page('view3dscene', 'view3dscene') . " allows to control
    GLSL shaders and even simply assign GLSL shaders
    (check out <i>Edit -&gt; Simply assign GLSL shader to all objects</i>
    menu item), so you can test your shaders with any 3D model.</p>

    <p><i>For programmers</i>: you may find useful my notes about shading languages in
    <a href=\"http://vrmlengine.svn.sourceforge.net/viewvc/*checkout*/vrmlengine/trunk/kambi_vrml_game_engine/opengl/examples/shading_langs/README\">shading_langs_demo/README</a>.</p>

  <li><p><b>Anti-aliasing</b> available (if multisampling is supported by
    graphic card). " . news_a_href_page('view3dscene', 'view3dscene') . "
    has comfortable menu <i>File -&gt; Startup Preferences -&gt; Anti aliasing</i>
    and also a command-line option <tt>--anti-alias</tt> to control this,
    " . news_a_href_page('castle', 'castle') . " has comfortable menu item
    in <i>Video options</i>.</p>

  <li><p><b>Collada model format</b> basic support (1.3.x and 1.4.x)
    added to the engine (" . news_a_href_page('view3dscene', 'view3dscene') . "
    and everything else open Collada files, just like any other model format;
    you can also convert Collada files to VRML 2.0).</p>
  </li>

  <li><p>Wavefront OBJ format handling improved (we handle normal vectors,
    materials, textures).</p>
  </li>

  <li><p><tt>Examine</tt> mode allows to rotate and move the scene by mouse
    dragging. This is more intuitive and sometimes comfortable.
    This feature is mostly noticeable in
    " . news_a_href_page('view3dscene', 'view3dscene') . ", although
    some example programs in engine demos also use such examine mode
    and also benefit from this.</p>
  </li>
</ul>

<p>Besides improvements above, some improvements specific to
" . news_a_href_page('view3dscene', 'view3dscene') . ":</p>

<ul>
  <li><tt>--screenshot</tt> command-line option, requested a few times,
    to take screenshots of the scene in batch mode.</li>
  <li>Various <i>fill modes</i>. Previously only <i>normal</i> and
    <i>wireframe</i> were available, now we have a couple more,
    like solid wireframe and silhouette. Keys changed (\"w\" key no longer works,
    \"f\" key changes fill mode instead of fog state).</li>
</ul>

<p>Also " . news_a_href_page('our Blender VRML 97 exporter script', 'blender_stuff') . "
improved: <i>set solid / set smooth / autosmooth / autosmooth degrees</i>
settings from Blender are correctly exported to VRML file (as creasteAngle field).</p>

<p>Other most notable internal engine changes:</p>

<ul>
  <li>I dropped my custom OpenGLh binding in favor of using GL, GLU, GLExt units
    from FPC. <i>Full story:</i> OpenGLh was developed when FPC had no usable OpenGL binding unit
    (neither had Delphi)... Times have changed, and current GL, GLU, GLExt
    units are usable, and (partially thanks
    <a href=\"http://www.mail-archive.com/fpc-devel@lists.freepascal.org/msg02328.html\">to</a>
    <a href=\"http://www.freepascal.org/mantis/view.php?id=7570\">my</a>
    <a href=\"http://www.freepascal.org/mantis/view.php?id=7600\">patches</a>,
    <a href=\"http://bugs.freepascal.org/view.php?id=10460\">quite</a>
    <a href=\"http://bugs.freepascal.org/view.php?id=10507\">a</a>
    <a href=\"http://bugs.freepascal.org/view.php?id=10508\">few</a> :) )
    they work good and support OpenGL 2.0 functions.
    (While OpenGLh was on the level of GL 1.2 + many extensions).</li>

  <li>Among many new demo programs, there's also
    <tt>kambi_vrml_game_engine/examples/vrml/plane_mirror_and_shadow.lpr</tt>
    to test plane-projected shadows and plane mirrors. Plane-projected shadows
    is only for simple demo (we have implemented shadow volumes, thousand times better
    algorithm, after all), but plane mirrors will be implemented
    in the future in the VRML engine (using \"mirror\" <tt>Material</tt>
    field automatically).</li>
</ul>

<p>Other releases:
" . news_a_href_page('Kambi VRML test suite 2.1.0', 'kambi_vrml_test_suite') . "
has many new tests/demos for new features (bump mapping, GLSL,
Collada format). Also released:
" . news_a_href_page('rayhunter 1.2.2', 'rayhunter') . ",
" . news_a_href_page('lets_take_a_walk 1.2.1', 'lets_take_a_walk') . ",
" . news_a_href_page('malfunction 1.2.4', 'malfunction') . ",
" . news_a_href_page('kambi_lines 1.1.4', 'kambi_lines') . ",
" . news_a_href_page('glplotter 1.2.1', 'glplotter_and_gen_function') . ",
" . news_a_href_page('glViewImage 1.2.2', 'glviewimage') . ",
" . news_a_href_page('bezier_curves 1.1.6', 'bezier_curves') . ",
" . news_a_href_page('glcaps 1.1.2', 'glcaps') . ",
mainly to bring them up-to-date with current engine state.</p>
"),

/* --------------------------------------------------------------------------- */

    array('title' => 'castle 0.8.0, view3dscene 2.3.0 released',
          'year' => 2007,
          'month' => 11,
          'day' => 17,
          'guid' => '2007-11-17',
          'short_description' =>
"<p>A lot of updates today. Here's a shortcut of only the most important changes
(see " . news_a_href_page('news', 'news') . " for a full list of changes) :
<ul>
  <li>" . news_a_href_page('"The Castle" 0.8.0', 'castle') . " released:
    new demo level <i>the fountain</i> (VRML 2.0, dynamic shadows),
    many shadows improvements (z-fail, proper detection z-pass/z-fail, shadow
    culling etc.), conserve memory feature (all Radeon issues should be fixed
    now).</li>
  <li>" . news_a_href_page('view3dscene 2.3.0', 'view3dscene') . " released:
    prototypes (both <tt>PROTO</tt> and <tt>EXTERNPROTO</tt>),
    VRML 2.0 lights are correctly handled,
    handling of colors for <tt>IndexedFaceSet</tt> and <tt>IndexedLineSet</tt>,
    <a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#ext_text3d\">Text3D extension</a>.</li>
  <li>" . news_a_href_page('Kambi VRML game engine 1.2.0', 'kambi_vrml_game_engine') . "
    released: most things mentioned above were actually implemented in the base
    engine units, also: x86-64 port.</li>
  <li>" . news_a_href_page('Kambi VRML test suite 2.0.0', 'kambi_vrml_test_suite') . "
    released: many new tests for new features.</li>
  <li>" . news_a_href_page('Blender VRML stuff page added, with improved VRML 2.0
    exporter and kanim exporter', 'blender_stuff') . ".</li>
  <li>Updated version of " . news_a_href_page('VRML engine documentation',
    'vrml_engine_doc') . " is available, with a
    <a href=\"http://vrmlengine.sourceforge.net/vrml_engine_doc/output/xsl/html/chapter.shadows.html\">chapter
    about shadows implementation.</a></li>
</ul>",
          'description' =>

"<p>" . news_a_href_page('"The Castle" 0.8.0', 'castle') . " released:

<ul>
  <li><p>New demo level: <i>the fountain</i>, done in pure VRML 2.0
    format (no more VRML 1.0). Shadows for whole level are generated dynamically.
    In the next release, this level is supposed to be augmented with some
    eye candy graphical effects, for now enjoy VRML 2.0 and shadows :)</p></li>

  <li><p>Shadows improvements (see also
    <a href=\"http://vrmlengine.sourceforge.net/vrml_engine_doc/output/xsl/html/chapter.shadows.html\">new chapter
    in documentation about shadows</a>) :</p>

    <ul>
      <li>First of all, z-fail implemented and proper detection when z-fail
        is needed implemented, so faster z-pass is used when possible.
        \"The Castle\" shows (toggle with Tab, just like for FPS) number
        of shadows qualified as z-pass, z-fail, z-fail with light cap needed etc.
      <li>Shadow volumes silhouette optimization improved: now models don't have
        to be perfect manifold to use this. See
        <tt>kambi_vrml_game_engine/examples/vrml/shadow_volume_test/</tt>
        demo, in particular the <tt>shadow_volume_test_ball_with_tentacles.sh</tt>
        example.</li>
      <li>Much better frustum culling for shadows.</li>
    </ul>
  </li>

  <li><p>Arrows are affected by gravity, and underwater \"sick\" projection
    effect, thanks to Grzegorz Hermanowicz (herrmannek).</li>

  <li><p>Numerous memory and speed optimizations to load VRML models and
    animations faster and better (thanks to valgrind (callgrind, massif)).
    Also in \"The Castle\" there's new <i>Conserve memory</i> feature
    (this basically means that only creature animations needed for current
    level are kept in memory), turned on by default.</p>

    <p>So \"Loading creatures\" is much less resource consuming.
    And finally pretty much all Radeon issues are fixed now.</li>

  <li>Fixed hang (actually, a really really long delay) when closing sound device
    on Linux (actually, with OpenAL sample implementation).</li>

  <li>Demo levels are available directly from \"New game\" menu now.</li>
  <li>Nicer credits screen.</li>
</ul>

<p>" . news_a_href_page('view3dscene 2.3.0', 'view3dscene') . " released:

<ul>
  <li>Prototypes (both <tt>PROTO</tt> and <tt>EXTERNPROTO</tt>)
    VRML 2.0 feature is fully implemented now !</li>
  <li>VRML 2.0 lights are correctly handled (<tt>DirectionalLight</tt>
    affects every sibling, positional lights affect whole scene taking
    <tt>radius</tt> into account).</li>
  <li><tt>ROUTE</tt> constructs of VRML 2.0 are parsed now.
    Although they still don't actually <b>do</b> anything.
    So at least scenes using routes are partially handled (routes are simply
    ignored), instead of just producing an error.</li>
  <li>Default blending dest factor for view3dscene is
    <tt>GL_ONE_MINUS_SRC_ALPHA</tt>, since this is expected by most VRML
    authors.</li>
  <li>VRML files compressed by gzip are handled OK even if they have
    normal <tt>.wrl</tt> extension.</li>
  <li>--write-to-vrml fixed</li>
  <li>Handling of colors (<tt>color</tt>, <tt>colorPerVertex</tt>,
    <tt>colorIndex</tt>) for <tt>IndexedFaceSet</tt> and <tt>IndexedLineSet</tt>
    done.</li>
  <li>NavigationInfo.speed is now handled correctly (it sets speed per second)</li>
  <li><a
    href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#ext_text3d\">Text3D extension</a>.</li>
</ul>

<p>" . news_a_href_page('Kambi VRML game engine 1.2.0', 'kambi_vrml_game_engine') . "
released. Most features mentioned above for view3dscene and castle
(shadows, optimizations, all VRML 2.0 features) are actually implemented
in the engine, and other programs only use them. Additionally, some
more internal features not mentioned above:</p>

<ul>
  <li><p>Engine is ported and works flawlessly on x86-64 on Linux.
    No more only 32-bit :) Also, it's partially ported to Windows x84-64
    (tested compilation with cross compiler, no actual run tests).</p>

    <p>This also results in the change of archive binary names:
    they all get <tt>i386</tt> after their name, eventually I may release
    precompiled versions for <tt>x86-64</tt> too.</p></li>

  <li><p>GLWindow allows to change cursor shape.</p></li>

  <li><p>Everything is compiled using new FPC 2.2.0.</p></li>
</ul>

<p>" . news_a_href_page('Kambi VRML test suite 2.0.0', 'kambi_vrml_test_suite') . "
released: many new tests to test new features (protos, external protos,
colors, light scope, running path to test NavigationInfo.speed, 3d text),
some important VRML 1.0 tests ported to VRML 2.0 too (castle,
relative_names, texture_test, house behind the glass).</p>

<p>" . news_a_href_page('Blender VRML stuff page added, with improved VRML 2.0
exporter and kanim exporter', 'blender_stuff') . ".</p>

<p>Updated version of " . news_a_href_page('VRML engine documentation',
'vrml_engine_doc') . " is available, with a chapter about shadows
implementation.</p>
"),

/* --------------------------------------------------------------------------- */

    array('title' => 'glplotter 1.2.0 and view3dscene 2.2.1 released',
          'year' => 2007,
          'month' => 9,
          'day' => 6,
          'guid' => '2007-09-06',
          'short_description' => '',
          'description' =>

"<ul>
  <li>" . news_a_href_page('glplotter 1.2.0 and gen_function 1.0.2',
    'glplotter_and_gen_function') . " released: glplotter GUI greatly improved:
    Open/Add menu items to open graphs from files
    and to generate graphs from function expressions.
    This means that now you don't have to specify graphs at command-line,
    and now you don't have to write pipes with gen_function.
    Also documentation and some options translated finally to English.</li>
  <li>" . news_a_href_page('view3dscene 2.2.1', 'view3dscene') . " released:
    bug fix release. Fixed crash when removing geometry node from
    VRML 2.0 hierarchy. Fixed jagged animation when world time was
    really large (usually occurs when \"on display\" time pass was
    really large for some time). Fixed messing the gravity
    \"up\" vector when reopening the scene.</li>
  <li>" . news_a_href_page('Kambi VRML game engine 1.1.1',
    'kambi_vrml_game_engine') . " released: changes needed by
    view3dscene and glplotter above.</li>
  <li><a href=\"http://vrmlengine.sourceforge.net/news_feed.php\">RSS
    feed</a> listing all changes is available now.
    <small>SouceForge already made RSS feeds for our project,
    but they didn't allow me HTML code there, and HTML links
    are simply useful for my news messages.</small></li>
</ul>"),

/* --------------------------------------------------------------------------- */

    array('title' => 'view3dscene 2.2.0 and related releases',
          'year' => 2007,
          'month' => 8,
          'day' => 25,
          'guid' => '2007-08-25',
          'short_description' => '',
          'description' =>

"<ul>
  <li>" . news_a_href_page('view3dscene 2.2.0', 'view3dscene') . " release:
    view3dscene can display animations now (for now in " .
     news_a_href_page(
    "Kanim (Kambi VRML engine animations) format", 'kanim_format') . " and
    MD3).</li>
  <li>" . news_a_href_page('Kambi VRML test suite 1.1.0',
    'kambi_vrml_test_suite') . " release: many kanim demos added.</li>
  <li>" . news_a_href_page('Kambi VRML game engine 1.1.0',
    'kambi_vrml_game_engine') . " release: many changes, for animations
    in view3dscene, also GLMenu and GameSoundEngine units added
    (some \"The Castle\" code improved and moved to a generally-usefull
    units area), bugfixes to MD3 texture handling.</li>
</ul>"),

/* --------------------------------------------------------------------------- */

    array('title' => 'Move to SourceForge finished',
          'year' => 2007,
          'month' => 7,
          'day' => 25,
          'guid' => '2007-07-25',
          'short_description' => '',
          'description' =>

"<p>The move of <i>Kambi VRML game engine</i> project to SourceForge is finished !
In fact, if you're reading this text, then you already view our page
as hosted on SourceForge.</p>

<p>Being on SourceForge gives us many new features, most important ones:
<a href=\"http://sourceforge.net/project/showfiles.php?group_id=200653\">file
downloads</a> use all the power and speed of SF mirrors,
development is done inside
<a href=\"http://vrmlengine.svn.sourceforge.net/viewvc/vrmlengine/\">publicly
visible SVN repository</a>, we have a public " . MAILING_LIST_LINK . ",
we have trackers for
<a href=\"" .  BUGS_TRACKER_URL . "\">bugs</a>,
<a href=\"" .  FEATURE_REQUESTS_TRACKER_URL . "\">feature requests</a>,
<a href=\"" .  PATCHES_TRACKER_URL . "\">patches</a>,
there's <a href=\"http://sourceforge.net/export/rss2_projfiles.php?group_id=200653\">RSS
feed to monitor new releases</a>.</p>"),

/* --------------------------------------------------------------------------- */

    array('title' => 'Moving to SourceForge: using SF download system',
          'year' => 2007,
          'month' => 7,
          'day' => 23,
          'guid' => '2007-07-23',
          'short_description' => '',
          'description' =>

"<p>Download links for most VRML stuff on this page direct to SourceForge
file release system now. This is another step in moving to
<a href=\"http://sourceforge.net/projects/vrmlengine\">vrmlengine
on SourceForge</a>.

<p>Also, some things now get version numbers:
" . news_a_href_page('Kambi VRML game engine', 'kambi_vrml_game_engine') . " (1.0.0),
" . news_a_href_page("Kambi VRML test suite", "kambi_vrml_test_suite") . " (1.0.0).
</p>"),

/* --------------------------------------------------------------------------- */

    array('title' => 'Older news',
          'year' => 2007,
          'month' => 7,
          'day' => 19,
          'short_description' => '',
          'description' =>
  '<!-- Older logs are available only in HTML, they were not converted
       to $news format. -->

  <div class="old_news_item"><p><a name="older_news"><span class="old_news_date">July 19, 2007:</span></a></p>

    <p>Just to let you know that my whole VRML stuff is on the move
    to <a href="http://sourceforge.net">SourceForge.net</a>.
    See <a href="http://sourceforge.net/projects/vrmlengine">vrmlengine
    project page on SourceForge</a>.</p>

    <p>I already use their SVN repository to host my code.
    Most of my whole private repository was imported there,
    so even though the repository on SF exists for less than a week, it already
    has 1800+ commits :). If you look close enough, you\'ll notice two games
    visible in the repository that were not released yet on these pages.
    They are available only from SVN sources for now:
    sandbox (a demo of isometric rendering) and "The Rift" (my current small
    project &mdash; a demo in the style of adventure games, with still background
    and 3d players; just a start for now).</p>

    <p>See ' . news_a_href_page('sources', 'sources') . ' and many other
    pages for detailed instructions how to get code out of SVN repository.</p></div>

  <div class="old_news_item"><p><span class="old_news_date">June 12, 2007:</span>
    <p>Finally, the great update happens ! Most important are
    ' . news_a_href_page('"The Castle" 0.7.0', 'castle') . ' and
    ' . news_a_href_page('view3dscene 2.1.0', 'view3dscene') . ' releases,
    and (for programmers) the underlying changes to
    ' . news_a_href_page('Kambi VRML game engine', 'kambi_vrml_game_engine') . '.
    Actually almost all programs on these pages are updated too
    (packaged in a different way, ported to Mac OS X, minor fixes).</p>

    <p>As for the question: <i>why didn\'t you update anything on these pages
    within the last 6 months ?</i>. My answer is: I was imprisoned in a cave.
    By a legion of elves. Really, hundreds of nasty bastards with
    pointy ears kidnapped me and threw me into a dark cave. Just today my cat
    managed to rescue me. :) Seriously, recently my life was just
    pretty occupied &mdash; I\'m on the 1st year of
    <a href="http://www.ii.uni.wroc.pl/cms/pl/teaching/phd_studies.html">Ph.D.
    Studies at the University of Wroc�aw</a> (in case you didn\'t notice the
    link to "Teaching" stuff at the top of the main page).
    Besides, well, life has been really good lately &mdash; thanks to K.O.
    and the mountains. :)

    <p>"The Castle" user-visible features:
    <ul>
      <li><b>New DOOM E1M1 level.</b>
      <li><b>Ported to Mac OS X.</b>
      <li><b>Shadows much improved.</b> Not totally finished yet
        (still only depth-pass),
        but much corrected and optimized. With good graphic card, the game is
        playable with shadows on. <i>Thanks go to Olgierd Hume�czuk for setting
        me on the right track.</i>
      <li>New controls features:
        <ul>
          <li>You can assign up to two keys and one mouse button for
            each action in "The Castle".
          <li>Default shortcuts changed to more resemble modern FPS games:
            use AWSD moving, left/right arrows rotate,
            E interacts (HalfLife2-like), R drops etc.
          <li>New keybinding to use "potion of life" if owned,
            <!-- (analogy of "m" for "medkit" in tremulous)-->
            default is "l" (lower "L").
            Very handy in the middle of the fight, when you really don\'t have time
            to search your inventory by the "[", "]" keys .
          <li>"Invert vertical mouse look" option.
        </ul>
      <li><tt>--screen-size</tt> command-line option.
      <li>On "Castle Hall" level: werewolves fight totally changed, to be much more
        interesting.
      <li>On "The Gate" level: teleport made closer, to reduce a little the need
        for jumping, scroll of flying easier to get with sword, doors at the end,
        minor fixes.
      <li>Footsteps sound now changes within level, depending on whether you
        walk on grass or concrete ground.
      <li>Much better demo (background behing main menu). Enjoy.
      <li>Alien model improved (better animation, manifold (for shadows),
        skin texture with ambient occlusion).
    </ul>

    view3dscene user-visible features:
    <ul>
      <li><b>MD3 (Quake3 engine) model format fully supported.</b>
      <li>Radio menu items in view3dscene.
      <li>Ability to select an item (point and it\'s triangle).
      <li>Edit menu items to remove selected geometry node and selected face.
      <li>Recently opened files menu.
      <li>In MouseLook mode, right/left keys work like strafe
        and comma/dot are for rotations.
      <li>Changing blending source and dest factors at runtime.
      <li>Menu item to show OpenGL capabilities.
      <li>Also ported to Mac OS X, actually the whole engine is ported to Mac OS X.
    </ul>

    <p>Most notable bugfixes:
    <ul>
      <li>Various problems specific to particular OpenGL implementations fixed:
        <ul>
          <li>Radeon: wire box is now drawn correctly.
          <li>Mesa, Radeon: volumetric fog rendering fixed.
          <li>Other small fixes for Mesa.
          <li>Some NVidia cards: life indicator alpha rendering fixed.
          <!-- li><b>Not all Radeon bugs are fixed yet, but they are planned to be
            fixed for the next release.</b -->
        </ul>
      <li>Libpng under Unixes loading fix (no longer need to install
        libpng*-dev packages).
    </ul>

    <p>Most notable engine internal improvements for programmers
    (of course not counting features and fixes that already got mentioned above... ) :
    <ul>
      <li>VRML camera is now correctly read, transformation of <tt>Viewpoint</tt>
        indicates gravity direction. This way you can set gravity up vector
        and initial camera up vector to different things.
      <li>New examples in "Kambi VRML game engine":<br />
        <tt>vrml/opengl/examples/shadow_volume_test</tt>,<br />
        <tt>opengl/examples/demo_matrix_navigation</tt>,<br />
        <tt>3d/examples/draw_space_filling_curve</tt>,<br />
        <tt>opengl/examples/fog_coord</tt>,<br />
        <tt>audio/examples/algets</tt> and<br />
        <tt>audio/examples/alplay</tt>.
      <li>Everything is in FPC objfpc mode, no longer Delphi compat mode anywhere.
      <li>Integration with FPC Matrix unit started (VectorMath now reuses
        non-object types from Matrix, many units use Matrix object types
        and overloaded operators).
      <li><tt>VRMLRayTracer</tt> interface changed to much cleaner object-oriented.
      <li>Detailed GL_VERSION and GLU_VERSION parsing and reporting,
        including the ability to detect Mesa and Mesa version.
        See GLVersion and GLUVersion objects.
      <li>"The Castle" excellent level objects framework: things that move and animate
        on the level are now much easier to add and design. This was already heavily
        used by DOOM E1M1 level, also see the nice elevator demo on "Tower" level.
      <li>TVRMLGLAnimation updated to work with VRML 2.0 SFNode and MFNode fields.
      <li>OggVorbis loading and playing (through OpenAL extension
        or vorbisfile library).
    </ul>

    <p>"The Castle" improvements for content (e.g. 3D level) designers.
    Many "The Castle" debug menu improvements and greatly improved game
    confugurability by editing game XML files:
    <ul>
      <!--li>"Render for level screenshot"-->
      <li>Configure sounds by <tt>sounds/index.xml</tt> file,
        debug menu option "Reload sounds.xml".
      <li>Configure items by <tt>items/kinds.xml</tt> file.
      <li>Blending type configurable for all items and creatures.
      <li>All animations are now expressed in external files
        (in *.kanim files or in XML nodes inside kinds.xml files).
      <!--li>"Fly" debug option.-->
      <li>Configure levels by <tt>levels/index.xml</tt> file.
        Many level properties, also hint boxes are configurable there.
      <li>New level "hello world".
      <li><tt>KambiHeadLight</tt> node to configure headlight from VRML.
      <li>No longer any need for "Transparent" properties. All creatures/items/levels
        can now freely mix transparent and opaque parts, and everything will
        be rendered always OK.
      <li>Octree params configurable from debug menu.
    </ul>

    <p>Also packaging changes: <tt>units-src</tt> renamed to
    <tt>kambi_vrml_game_engine-src</tt>,
    <tt>kambi.cfg</tt> file is included inside, <tt>test_kambi_units</tt>
    is included inside. Most programs package names include their version numbers.

    <p>Minor programs releases:
    ' . news_a_href_page('rayhunter 1.2.1', 'rayhunter') . ',
    ' . news_a_href_page('lets_take_a_walk 1.2.0', 'lets_take_a_walk') . ',
    ' . news_a_href_page('glViewImage 1.2.1', 'glviewimage') . ',
    ' . news_a_href_page('glplotter 1.1.6', 'glplotter_and_gen_function') . ',
    ' . news_a_href_page('glcaps 1.1.1', 'glcaps') . ',
    ' . news_a_href_page('gen_funkcja 1.0.1', 'glplotter_and_gen_function') . ',
    ' . news_a_href_page('bezier_curves 1.1.5', 'bezier_curves') . ',
    ' . news_a_href_page('malfunction 1.2.3', 'malfunction') . ',
    ' . news_a_href_page('kambi_lines 1.1.2', 'kambi_lines') . '.
    </div>

  <div class="old_news_item"><p><span class="old_news_date">February 28, 2007:</span>
    <p>Hello! It\'s been a while without any significant update on this page &mdash;
    so I thought that I just let you all know that the work on
     ' . news_a_href_page('"The Castle"', 'castle') . ' and
     ' . news_a_href_page('Kambi VRML game engine', 'kambi_vrml_game_engine') . '
     was ongoing in these last months.
    0.7.0 release of "The Castle", 2.1.0 release of view3dscene along
    with releases of most other programs on this page are scheduled
    within a week or two. A lot of internal features (usable for programmers
    wanting to use my engine, or 3D content designers for "The Castle") were done,
    along with a lot of bugfixes and many small feature additions.</p></div>

  <div class="old_news_item"><p><span class="old_news_date">October 7, 2006:</span>
    <p>Good news for FreeBSD users: I finally upgraded my FreeBSD to 6.1,
    and got NVidia OpenGL working smoothly there, along with OpenAL.
    So I updated all FreeBSD binaries on these pages to their latest version.
    I also confirmed that ' . news_a_href_page('"The Castle"', 'castle') . '
     compiles and works perfectly under FreeBSD (although the FreeBSD binary
    is not included in the game archive yet).</div>

  <div class="old_news_item"><p><span class="old_news_date">October 1, 2006:</span>
    <p>A made a new page about my
    ' . news_a_href_page('Kambi VRML game engine', 'kambi_vrml_game_engine') . '.
     Most of the content of this page was already said here and there,
    but now I want to say it more explicitly: <em>I\'m making a reusable game
    engine</em>. Also the engine sources are updated now, three new example
    programs are added: <tt>images/examples/image_convert</tt>,
    <tt>opengl/examples/test_font_break</tt> and
    <tt>opengl/examples/multi_glwindow</tt>.</div>

  <div class="old_news_item"><p><span class="old_news_date">September 27, 2006:</span>
    <p>Final version of
    ' . news_a_href_page("my master's thesis about my VRML engine",
      'vrml_engine_doc') . ' is available now.</p></div>

  <div class="old_news_item"><p><span class="old_news_date">September 21, 2006:</span>

    <p>Newest version of
    ' . news_a_href_page("my master's thesis about my VRML engine",
      'vrml_engine_doc') . ' is available. Only the 7th chapter remains
    undone. <em>Later update the same day: all chapters done!</em></p>

    <p>Units ' . news_a_href_page('sources', 'sources') . ' updated:
    included is an example how to do fog culling (to the fog visibility range),
    see the file <tt>units/vrml/opengl/examples/fog_culling.dpr</tt>.
    Also blending source and dest factors are now configurable.
    Also behavior on incorrect <tt>Background</tt> nodes is now better
    (reports warning and proceeds).</p></div>

  <div class="old_news_item"><p><span class="old_news_date">September 13, 2006:</span>

    <p>First of all, a draft and unfinished version of
    ' . news_a_href_page("my master's thesis about my VRML engine",
    'vrml_engine_doc') . ' is available.</p>

    <p>' . news_a_href_page('view3dscene 2.0.1', 'view3dscene') . '
     released &mdash; small updates and fixes. New menu items
    were added to display the whole octree and to change the
    point size of <tt>PointSet</tt>.
    The quadric stacks value (for the command-line option
    <tt>--detail-quadric-stacks</tt>
    and <tt>KambiTriangulation</tt> node) can be 1 now.
    The recently released FPC 2.0.4 is used to compile view3dscene now.</p>

    <p>Also, <a href="http://freshmeat.net/projects/view3dscene/">view3dscene
    entry was added to freshmeat</a>. You can use this e.g. to subscribe to new
    releases, so that you will be automatically notified about new
    releases of view3dscene.</p>

    <p>In ' . news_a_href_page('VRML test suite',
      'kambi_vrml_test_suite') . '
     <tt>vrml_2/kambi_extensions/fog_linear_with_immune.wrl</tt> test fixed.</div>

  <div class="old_news_item"><p><span class="old_news_date">August 24, 2006:</span>

    <p>First of all, I\'m proud to announce that
    <b>VRML 2.0 (aka VRML 97) support is implemented now</b>.
    It\'s by no means complete yet, but it\'s definitely usable
    already &mdash; see ' . news_a_href_page('VRML implementation status',
    'vrml_implementation_status') . ' for details and results of
    various test suites. Almost all of my
    ' . news_a_href_page("non-standard VRML extensions",
      "kambi_vrml_extensions") . ' work in VRML 2.0 too, and actually
    you can even ' . news_a_href_page_hashlink(
      "mix VRML 1.0 and 2.0 features in your files",
      "kambi_vrml_extensions", 'section_ext_mix_vrml_1_2') . '.

    <ul>
      <li>' . news_a_href_page('view3dscene 2.0.0', 'view3dscene') . '
        released &mdash; VRML 2.0 suppport,
        various other improvements: "Jump to viewpoint" menu added
        (this is useful both for VRML 2.0 Viewpoint nodes and VRML 1.0
        cameras too), --camera-pos, --camera-dir, --camera-up,
        --camera-up-z, --camera-kind, --view-angle-x command-line options
        removed (all these properties (and much more) can be set now
        by appropriate Viewpoint/camera nodes in the file; I decided that
        keeping these options was an unnecessary complication of implementation),
        menu disabling implemented, warnings while loading VRML file are
        stored and can be later viewed from the GUI using "View warnings"
        menu item, added "Reopen" menu item, added "Edit" menu
        (to perform interactively all the things previously
        controlled by <tt>--scene-change-*</tt> command-line options;
        <tt>--scene-change-*</tt> command-line options remain to work
        but only for the first loaded scene, so they are mostly useful
        when combined with <tt>--write-to-vrml</tt>).
      <li>' . news_a_href_page('Kambi VRML test suite',
        'kambi_vrml_test_suite') . ' &mdash; this was previously
        known on these pages as "kambi_vrml_examples.tar.gz", or "Example VRMLs".
        Many test cases were added
        for VRML 2.0, some of which were translated from VRML 1.0,
        some are new, some are created with Blender\'s VRML 97
        exporter. These VRML files are now officially licensed on GNU GPL.
      <li>' . news_a_href_page('rayhunter 1.2.0', 'rayhunter') . '
        released &mdash; VRML 2.0 support.
      <li>' . news_a_href_page('rayhunter gallery', 'raytr_gallery') . ' &mdash;
        added <i>mirror fun</i> rendering, demonstrating mirror effect
        in one of the first rayhunter renderings of VRML 2.0 model.
      <li>' . news_a_href_page('"The Castle" 0.6.6', 'castle') . '
        released &mdash; in 0.6.5 sky on the "Gate" level
        was not visible, fixed now. Also suppport for designing levels
        in VRML 2.0 added, but not finished yet, see TODO item on
        ' . news_a_href_page('"The Castle" &mdash; development',
        'castle-development') . ' page.
      <li>' . news_a_href_page('glViewImage 1.2.0', 'glviewimage') . '
        released,
        ' . news_a_href_page('glplotter 1.1.5', 'glplotter_and_gen_function') . ' released,
        ' . news_a_href_page('bezier_curves 1.1.4', 'bezier_curves') . ' released
         &mdash; updated to inherit many improvements in
        OpenGL and images units: menu disabling,
        and GIF images reading (by running ImageMagick under the hood),
        fixed handling of PNG files with alpha channel recorded in tRNS chunk.
      <li>' . news_a_href_page('lets_take_a_walk 1.1.5', 'lets_take_a_walk') . '
        released,
        ' . news_a_href_page('malfunction 1.2.2', 'malfunction') . '
        released
         &mdash; small fixes and generally updated
        to compile with latest version of VRML units.
      <li>' . news_a_href_page('Sources', 'sources') . ' and
        ' . news_a_href_page('sources documentation', 'reference') . '
        updated with all improvements mentioned above.
      <li><tt>edytorek</tt> is removed from these pages.
        Reasoning: I was not using it, not developing it, and I lost my interest
        in it long time ago. Since a long time I use Emacs as my only
        text editor, under all OSes. So there were a couple of embarassing
        issues with <tt>edytorek</tt> : it was Windows-only, it was
        compiled with Delphi Personal, and I didn\'t publish here it\'s source code...
        All these issues are quite embarassing for someone who
        uses Linux and FreePascal as his main work tools, and develops
        open-source programs... Of course I intended to clean edytorek
        code, porting it to Lazarus and publish it\'s sources some day,
        but, honestly, I don\'t think that it will ever happen.
        So, goodbye <tt>edytorek</tt>.
    </ul>
    </div>

  <div class="old_news_item"><p><span class="old_news_date">August 1, 2006:</span>
    <p>New version of ' . news_a_href_page('"The Castle"', "castle") . '
     (0.6.5) released: whole documentation is in HTML (available
    both here online and offline inside <tt>documentation/</tt>
    subdirectory, README file inside archive doesn\'t contain much now),
    <tt>--debug-log</tt> option will print lots of debug info,
    lifeloss when falling down lowered (to avoid getting hurt too easily
    when jumping), removed one scroll of flying from "Castle Hall"
    (to make the trick with flying over creatures harder),
    many other small changes and fixes.

    <p><i>My nearest development plans</i>:
    <ul>
      <li>At the end of this week I plan to finally
        upload here basic (static, without any PROTOs) VRML 97 support
        for ' . news_a_href_page('view3dscene', 'view3dscene') . ' !

      <li>Unfortunately further "The Castle" development is going to be suspended
        until the end of September. At the end of September I should do to
        "The Castle" two things: 1. add a small joke/experiment level
        (it\'s already partially done &mdash; you\'ll see what is this :) and
        2. finally fix these Radeon issues. This will result in 0.7.0 release.
        So stay tuned.
    </ul>
    </div>

  <div class="old_news_item"><span class="old_news_date">July 12, 2006:</span>
    <ul>
      <li>New program is available:
        <a href="http://michalis.ii.uni.wroc.pl/~michalis/grammar_compression.php">grammar_compression</a>
        &mdash; implementation of Sequitur and Sequential compression algorithms
        in ObjectPascal.

      <li>' . news_a_href_page('"The Castle"', "castle") . ' page
        is reworked, I created separate page for "developer" stuff.
        I want to move most information from Castle\'s README file to these WWW pages,
        and then replace README file with offline version of these pages.
    </ul>
    </div>

  <div class="old_news_item"><span class="old_news_date">July 3, 2006:</span>
    <ul>
      <li>
        ' . news_a_href_page('view3dscene 1.2.5', 'view3dscene') . '
        released &mdash; more usable behavior
        on errors while loading the scene: previously loaded state is preserved,
        errors when loading command-line scene are shown in the GUI,
        various other small usability improvements, some new/changed menu items.</li>

      <li>' . news_a_href_page('Base units', 'sources') . '
        updated with many internal changes &mdash;
        Added view3dscene_mini_by_lazarus: example that you can use all my VRML
        rendering code within "normal" Lazarus program, using Lazarus
        TOpenGLControl. test_kambi_units updated.
        Many "var" parameters changed to "out" to get more sensible FPC hints.</li>

      <li>' . news_a_href_page('glplotter 1.1.4', 'glplotter_and_gen_function') . '
        released &mdash; separate X and Y scaling available.</li>
    </ul>
    </div>

  <div class="old_news_item"><p><span class="old_news_date">June 8, 2006:</span>
    <p>New version of ' . news_a_href_page('"The Castle"', 'castle') . ' (0.6.4)
    released. Various small improvements and one important fix:
    open-source Radeon drivers
    under Linux are reported to work correctly right now. Unfortunately, issues
    with proprietary Radeon drivers (under Windows, and probably under Linux too)
    are not fixed yet &mdash; so stay tuned :)

    <p>In an unrelated news: For those of you who know my old alternative
    email address <tt>mkambi@poczta.onet.pl</tt>: don\'t use this address anymore.
    I will not receive mail send to this address.
    If you recently (in May 2006 or later) send a mail to this adress,
    then I probably didn\'t get it.
    <b>My only vaild email address is now
    ' . href_mailto(MICHALIS_EMAIL) . '.</b>
    Or you can send your mail to
    ' . href_mailto(MICHALIS_SF_EMAIL) . ', this will always be aliased
    to some of my valid email addresses.

    <!--
      Updates not deserving mention:
      - Also castle page improved a little, added text from my PGD forum
        post (1st "overview" sentence + "requirements to run.
      - Also version numbers added on every www page for program.
      - Uploaded src: view3dscene, malfunction, lets_take_a_walk,
        to keep them in compileable state.
    -->
    </div>

  <div class="old_news_item"><span class="old_news_date">May 19, 2006:</span>
    <p>New version of ' . news_a_href_page('"The Castle"', 'castle') . ' (0.6.3)
    released. Various "cleaning" changes and fixes:
    <ul>
      <li>Memory use reduced, this also reduced loading time.

        <p><i>Comparison:</i>
        Times and memory use below were measured on Linux with release build,
        with NVidia drivers. Note that the actual times and memory use may vary
        wildly from one graphic driver to the other, as the most time and memory
        consuming tasks are in preparing OpenGL things, like display lists
        and textures. For example, on Windows, memory consumption is slightly
        lower, which indicates that NVidia drivers are slightly better optimized
        for memory use on Windows.
        But hopefully the proportions will be around the same.
        Times below were measured for entering "New Game" -> "The Gate" level.

    <pre>
    0.6.2 version times:
      Loading level: 10 sec
      Loading creatures: 41 sec
      Loading items: ~ 4 sec
      Memory use: 496 MB
    0.6.3 version times:
      Loading level: 10 sec (nothing optimized here for time)
      Loading creatures: 24 sec
      Loading items: ~ 2 sec
      Memory use: 278 MB
    </pre>

        A lot of improvements to how we store and generate TVRMLGLAnimation
        instances was done for this.

      <li>"Debug menu" is now separated from normal game menu,
        is invoked by ~ key, default FPS toggle key is Tab now.
        This is all to make debug menu (a little) more hidden.
        Also debug menu has now submenus for commands related to:
        player, creatures and items. Added commands "Set Player.MaxLife"
        and "Reload animations/models of specific item".

      <li>Error messages fixed on Linux, previously various errors
        (e.g. in level or creatures VRML models) produced
        "ModeGLEnter cannot be called on a closed GLWindow" message
        instead of the right message.

      <li>--display command-line option for XWindows (but is sometimes
        unstable, probably because of NVidia OpenGL unstabilities)

      <li>Applied patches from Szymon Stoma &amp; Ka�ka Zaremba to improve
        texturing of some places on "The Gate".

      <li>MouseLook can be turned off.

      <li>Proper texture filtering is used for items, creatures and all level
        parts. Previously items, creatures and StairsBlocker on castle_hall
        used bad filtering (always GL_LINEAR, while e.g. GL_LINEAR_MIPMAP_LINEAR
        was noticeably better; in general, "Video options -> Texture quality"
        setting should be used).
    </ul>

    <p>Also ' . news_a_href_page('view3dscene', 'view3dscene') . ' (version 1.2.4)
    released: mouse look available (use "m" key). Just like in "The Castle".

    <!-- Silent new version of malfunction (1.2.1),
         precompiled only for Linux,
         to bring some fixes and source updates (for compat with my units). -->
    </div>

  <div class="old_news_item"><span class="old_news_date">May 9, 2006:</span>
    <p>New version of ' . news_a_href_page('"The Castle"', 'castle') . ' (0.6.2)
    released. Changes from version 0.6.0 include:
    right mouse button now does jumping, sound of SpiderQueen hurt fixed,
    SpiderQueen adjusted &mdash; overall it\'s a little easier to defeat now,
    although some details make it also harder (life decreased,
    the trick with jumping/flying on SpiderQueen is now much harder),
    bow + arrows added, Werewolf has higher life now.
    This was all done for version 0.6.1, that was final version for PGD
    competition. Version 0.6.2 brings only minor corrections to README and
    "Credits" text.</div>

  <div class="old_news_item"><span class="old_news_date">May 8, 2006:</span>
    <p>New version of ' . news_a_href_page('"The Castle"', 'castle') . ' (0.6.0)
    released. This is final (or almost-final) version for the PGD competition.
    List of changes since 0.5.9 version is huge, among the most important
    features are: new level "Cages" with new creatures,
    much reworked level "The Gate" (thanks to Szymon Stoma and Ka�ka Zaremba),
    new features making creatures harder to beat
    (homing missiles, knockback for player, now so easy to interrupt boss
    attack, aliens try to always stay away from you,
    most creatures are generally faster), life indicator for bosses,
    and an ending sequence is done.

    <p>Also ' . news_a_href_page('view3dscene', 'view3dscene') . ' (version 1.2.3)
    released with some improvements:
    <ul>
      <li>removed FreeWalk navigation method, instead now you can
        freely control PreferHomeUpForRotations/Moving from menu.
      <li>ambientIntensity from VRML 97 implemented for lights.
    </ul>
    </div>

  <div class="old_news_item"><span class="old_news_date">May 4, 2006:</span>
    <p>New version of ' . news_a_href_page('"The Castle"', 'castle') . ' (0.5.9)
    released: creatures are now configurable by <tt>kinds.xml</tt> file,
    <tt>--debug-no-creatures</tt> command-line option,
    you can set color bit depth and display frequency (the last feature is actually
    honoured only on Windows for now &mdash; yeah, I\'m under the pressure :) ).
    "Official" downloadable version is still 0.5.6,
    <a href="http://stoma.name/michalis/castle-with-sources-0.5.9.tar.gz">version 0.5.9
    compiled only for Linux is here</a>.
    </div>

  <div class="old_news_item"><span class="old_news_date">May 3, 2006:</span>
    <p>New version of ' . news_a_href_page('"The Castle"', 'castle') . ' (0.5.8)
    released: debug menu for lights improved (ambientIntensity for lights,
    "Edit headlight", "Global Ambient Light"), some other small things.
    There\'s also a new level, but it\'s hidden &mdash; don\'t look at it now,
    should be finished tomorrow.
    "Official" downloadable version is still 0.5.6,
    <a href="http://stoma.name/michalis/castle-0.5.8.tar.gz">version 0.5.8
    compiled only for Linux is here</a>.
    </div>

  <div class="old_news_item"><span class="old_news_date">May 1, 2006:</span>
    <p>New version of ' . news_a_href_page('"The Castle"', 'castle') . ' (0.5.7)
    released: debug menu item to change jump properties,
    debug menu item to edit level lights, some other small fixes.
    "Official" downloadable version is still 0.5.6,
    <a href="http://stoma.name/michalis/castle-0.5.7.tar.gz">version 0.5.7
    compiled only for Linux is here</a>.
    </div>

  <div class="old_news_item"><span class="old_news_date">April 30, 2006:</span>
    <p>New version of
    ' . news_a_href_page('"The Castle"', 'castle') . ' (0.5.6)
    released: trying to nail down display bugs on Radeon:
    checking display lists availability,
    "Creature animation smoothness" and "Restore to defaults" in "Video options".
    </div>

  <div class="old_news_item"><span class="old_news_date">April 29, 2006:</span>
    <p>New version of
    ' . news_a_href_page('"The Castle"', 'castle') . ' (0.5.5)
    released: many small pending features/bugfixes done: you can restart
    "New Game" from any level that you once managed to get to,
    when changing keys assignment and the conflict is found you can just clear
    the assignment of the old key (Eric Grange idea), fixes when floating
    just above the water, player moving speeds adjusted better,
    better navigation when flying/swimming (you can go up/down just
    by looking up/down), fixed walking down from slight hills,
    fixed accidentaly moving adjacent menu items sliders,
    left/right keys are by default assigned to left/right strafes now,
    some others.</div>

  <div class="old_news_item"><span class="old_news_date">April 27, 2006:</span>
    <p>New version of
    ' . news_a_href_page('"The Castle"', 'castle') . ' (0.5.4)
    released: mouse looking implemented. Also "The Castle" archives
    are now hosted on much faster server provided by
    <a href="http://stoma.bestweb.pl/">Szymon</a> (thanks!).</div>

  <div class="old_news_item"><span class="old_news_date">April 26, 2006:</span>
    <ul>
      <li>New preview version of
        ' . news_a_href_page('"The Castle"', 'castle') . ' (0.5.3)
        released: <b>sounds and music are done !</b>,
        and various "Gate" level improvements (like swimming).
      <li>' . news_a_href_page('lets_take_a_walk', 'lets_take_a_walk') . '
        (ver 1.1.4) release &mdash; bugfix for newer OpenAL
        under Linux that don\'t include alut functions in the same SO file.
      <li>Inside units sources, there\'s new unit
        <tt>ALSourceAllocator</tt> and it\'s demo in <tt>audio/examples/</tt>.
        This is an intelligent manager of OpenAL sounds,
        used in ' . news_a_href_page('The Castle', 'castle') . '.
    </ul>
    </div>

  <div class="old_news_item"><span class="old_news_date">April 17, 2006:</span>

    <ul>
      <li>First of all, ' . news_a_href_page('a preview of my new game "The Castle" is available',
        'castle') . '. This is the project that I am working on since February
        this year. Everyone is most welcome to download and try it !

      <li><p>Updated ' . news_a_href_page('view3dscene', 'view3dscene') . '
        (ver 1.2.2):

        <p>VRML extensions:
        <ul>
          <li>' . news_a_href_page_hashlink(
            'Field <tt>separate</tt> for <tt>WWWInline</tt> node',
            'kambi_vrml_extensions', 'section_ext_wwwinline_separate') . '.
          <li>' . news_a_href_page_hashlink(
            '<tt>Fog</tt> node extensions to define volumetric fog',
            'kambi_vrml_extensions', 'section_ext_fog_volumetric') . '
          <li>' . news_a_href_page_hashlink(
            '<tt>fogImmune</tt> field for <tt>Material</tt> node',
            'kambi_vrml_extensions', 'section_ext_fog_immune') . '
        </ul>

        <p>Also head bobbing much better, and various other improvements.

      <li><p>Important updates to demo_animation
        (see <tt>units/vrml/opengl/examples/</tt>) in the
        ' . news_a_href_page('sources', 'sources') . ':
        <ul>
          <li>New demo (gus) showing how to use Blender "armature" animation
            to export animation to VRMLs such that demo_animation is able to
            render it. This is quite great, because this allows you to very
            comfortably design animations in Blender and then use them with my engine.
          <li>Important fix for animating models with textures, demo (cube_opening)
            added.
          <li>Animating class <tt>TVRMLGLAnimation</tt> extended to be able
            to animate / morph between an atritrary number of models (>= 2),
            not only 2. Each model has an associated point of time in the
            animation. Demo (gus_3_final, to be used together with gus_1_final
            and gus_2_final) added.
          <li>Automatic looping and going backwards ability for TVRMLGLAnimation.
            See --loop, --no-loop, --backwards and --no-backwards command-line
            options for demo_animation.
        </ul>

      <li><p>Updated glViewImage (ver 1.1.5) (various small fixes).

      <li><p>Updated many other programs sources to keep them compileable,
        because of many changes in units, and some other various
        small fixes.

      <li><p>Oh, and I put here my
        <a href="http://michalis.ii.uni.wroc.pl/~michalis/michalis-gpg-public-key.asc">public GPG key</a>.
    </ul>
    </div>

  <div class="old_news_item"><span class="old_news_date">March 9, 2006</span><br>
    Many ' . news_a_href_page('view3dscene', 'view3dscene') . ' (ver 1.2.1)
    updates:
    <ul>
      <li>VRML 97 nodes
        ' . news_a_href_page_hashlink('NavigationInfo',
        'kambi_vrml_extensions', 'section_ext_navigationinfo') . ' and
        ' . news_a_href_page_hashlink('WorldInfo',
        'kambi_vrml_extensions', 'section_ext_worldinfo') . ' handling,
        ' . news_a_href_page('kambi_vrml_test_suite',
        'kambi_vrml_test_suite') . ' has test VRMLs for this.

      <li>More work on gravity stuff: growing up to camera height
        (allows climbing stairs etc.), nice effect when falling down from high,
        jumping ("A" key), crouching ("Z" key), head bobbing.
        Strafe move keys changed (to not collide with "Z" and to be
        more standard): "Comma" / "Period". Also horizontal moving
        in Walk mode is now better (moving dir is not affected by PageUp/PageDown
        keys operations, i.e. current vertical rotation). And vertical rotations
        (PageUp/PageDown keys) are bounded, so that you\'re no longer able to "stand
        on your own head".

      <li><i>Console</i> -> <i>Print scene bounding box as VRML node</i> menu
        command.
    </ul>

    <p>See also screenshots of my game "The Castle" (<i>link not available
    anymore</i>).
    This is the main thing that I\'m working on right now, it\'s for the
    <a href="http://pascalgamedevelopment.com/">PascalGameDevelopment</a>
    competition.

    <!-- Silently updated
      lets_take_a_walk src.
      rayhunter src.
    just to keep them in compileable state -->
    </div>

  <div class="old_news_item"><span class="old_news_date">February 24, 2006</span><br>
    Many updates. Every OpenGL based program updated,
    ' . news_a_href_page('all units and programs sources', 'sources') . '
    updated (along with ' . news_a_href_page('their documentation', 'reference') . ').
    Most important things are
    <ul>
      <li>Timing bug fixed in every OpenGL program
      <li>Compilation of many examples fixed
      <li>"Sunny day" level of
        ' . news_a_href_page('malfunction', 'malfunction') . '
        completely reworked and improved
      <li>' . news_a_href_page('view3dscene', 'view3dscene') . '
        up/down navigation improved and "gravity" setting added
    </ul>

    <p>Detailed changes log follows:

    <p>General updates:
    <ul>
      <li>Timing bug fixed (timing was sometimes incorrect, which
        caused some bad artifacts when moving camera or doing some
        animations &mdash; this was particularly observed with newest
        NVidia Linux OpenGL drivers).
      <li>Compilation of many examples fixed; sorry, I wasn\'t compiling examples
        too often and recently I broke many of them (bacause of changes to
        <a href="' . CURRENT_URL . 'apidoc/html/KambiUtils.html#Parameters">Parameters</a>
        stuff). It\'s fixed now. I also added the automatic test of compilation
        to the script I use to create <tt>units-src.tar.gz</tt> archive, so
        this Will Not Happen Again.
      <li>Fullscreen toggle shortcut is F11 (following (GNOME) standards
        &mdash; epiphany, GIMP, gthumb and firefox).
      <li><a href="http://www.freepascal.org/bugs/showrec.php3?ID=4831">FPC 2.0.2 bug #4831</a>
        workarounded (this caused some rare problems when displaying dialog boxes).
    </ul>

    <p>' . news_a_href_page('malfunction', 'malfunction') . ' (ver 1.2.0)
    specific updates:
    <ul>
      <li>"Sunny day" level completely reworked and improved.
      <li>malfunction sources contain now Blender files used to create all
        objects and levels, see devel_data/ subdirectory.
    </ul>

    <p>' . news_a_href_page('view3dscene', 'view3dscene') . ' (ver 1.2.0)
    specific updates:
    <ul>
      <li>Small interface improvements: +/- keys (move speed change) work
        now better (time-based), progress bar is shown in OpenGL window
        when opening scene using "Open" menu item,
        Cancel key for raytracer dialog, "Navigation" submenu.
      <li>Fixed bug that occured for specific models with empty Coordinate3 node
        followed by empty IndexedFaceSet node &mdash; this triggered OpenGL
        error "invalid value" in some cases, now it doesn\'t.
      <li>Better Insert/Delete navigation (vertical moving with respect
        to home camera up, instead of current camera up) in Walk mode.
        ' . news_a_href_page('lets_take_a_walk', 'lets_take_a_walk') . '
        also benefits from this.
      <li>Gravity setting: you can now turn gravity on, and fall down.
        Basic implementation committed, expect more work on this soon
        (including "jump" and "duck" keys and head "bobbing") &mdash; I\'m porting
        stuff from some very old game of mine into
        <a href="' . CURRENT_URL . 'apidoc/html/MatrixNavigation.TMatrixWalker.html">TMatrixWalker</a>
        class.
    </ul>

    <p>' . news_a_href_page('glViewImage', 'glviewimage') . ' (ver 1.1.4)
    small improvement (accepts dir name on command-line).

    <p>Other OpenGL programs updated:
    ' . news_a_href_page('lets_take_a_walk', 'lets_take_a_walk') . ' (ver 1.1.3),
    ' . news_a_href_page('glplotter', 'glplotter_and_gen_function') . ' (ver 1.1.3),
    ' . news_a_href_page('bezier_curves', 'bezier_curves') . ' (ver 1.1.3),
    ' . news_a_href_page('kambi_lines', 'kambi_lines') . ' (ver 1.1.1).
    Also ' . news_a_href_page('sources docs page', 'reference') . '
    shortened, ' . news_a_href_page('kambi_vrml_test_suite',
    'kambi_vrml_test_suite') . ' repackaged and fixed
    some links to www.web3d.org VRML specification.

    <p><i>Last-minute note about FreeBSD</i>:
    compiled programs for FreeBSD <i>will not be updated</i> today.
    I\'m sorry, but currently I have terrible problems with OpenGL on
    FreeBSD &mdash; current NVidia drivers (8178) cause
    kernel crashes (it seems that they didn\'t really update their
    drivers to FreeBSD 6 ?), and Mesa is terribly unstable.
    I checked various OpenGL programs, including Mesa demos, and
    they all just fail in various mysterious ways (segfaults, hangs, etc.).
    So it\'s not a problem specific to my programs &mdash; it\'s some
    problem with my FreeBSD setup, but I don\'t have time to fight
    with it now. Anyway, after compiling, I was unable to actually
    test my programs on FreeBSD,
    so I will not upload here completely untested binaries.
    If you use FreeBSD, feel free to just compile them yourself.</div>

  <div class="old_news_item"><span class="old_news_date">February 13, 2006</span><br>
    Many modifications to my general units done, so
    ' . news_a_href_page('all units and programs sources', 'sources') . '
    are updated. As usual,
    ' . news_a_href_page('documentation generated by pasdoc',
    'reference') . ' is also updated.

    <p>New unit VRMLGLAnimation was created, to easily produce animations
    from still scenes.
    See extensive demo in <tt>vrml/opengl/examples/demo_animation.dpr</tt>,
    with raptor and sphere sample models.

    <p>I\'m also glad to add that I\'m starting in
    <a href="http://pascalgamedevelopment.com/">Pascal Game Development</a>
    game competition. This should result in a new game available on these
    pages around April 2006, and between February and April 2006 I will
    constantly update my units on these pages.</div>

  <div class="old_news_item"><span class="old_news_date">January 16, 2006</span><br>
    And once again, ' . news_a_href_page('documentation generated by pasdoc',
      'reference') . ' and units
      ' . news_a_href_page('sources', 'sources') . ' updated again:
    a lot of content translated to English.</div>

  <div class="old_news_item"><p><span class="old_news_date">December 11, 2005</span><br>
    ' . news_a_href_page('Documentation generated by pasdoc', 'reference') . '
    and units ' . news_a_href_page('sources', 'sources') . ' updated again:
    much more impressive introduction page, old README_GLOBAL file removed,
    many things translated to English. Some issues with compilation
    with FPC 2.0.2 fixed.</div>

  <div class="old_news_item"><p><span class="old_news_date">November 27, 2005</span><br>
    ' . news_a_href_page('Documentation generated by pasdoc', 'reference') . '
     and units ' . news_a_href_page('sources', 'sources') . ' updated
    to reflect many new features and improvements in
    <a href="http://pasdoc.sourceforge.net/">PasDoc 0.10.0</a> released yesterday.</div>

  <div class="old_news_item"><p><span class="old_news_date">November 12, 2005</span><br>
    ' . news_a_href_page("lets_take_a_walk", "lets_take_a_walk") . '
    1.1.2 released (only for Linux) &mdash; fixed problem with linking to
    current Debian-testing openal version.</div>

  <div class="old_news_item"><p><span class="old_news_date">October 2, 2005</span><br>
    A lot of content on these pages finally translated to English.
    New versions of most programs released, with updated documentation
    and often other improvements. Full list of changes:
    <ul>
      <li>
      ' . news_a_href_page("Specification of my extensions to VRML",
        "kambi_vrml_extensions") . ',
        ' . news_a_href_page(
        "standard command-line options understood by all my OpenGL programs",
        "opengl_options") . '
        pages completely translated to English. Polish versions removed.
      <li>' . news_a_href_page("malfunction", "malfunction") . ' 1.1.0
        released &mdash; help text is now in English,
        complete English documentation. Polish docs removed.
      <li>' . news_a_href_page("view3dscene", "view3dscene") . ' 1.1.3
        released &mdash; now English documentation is complete
        (docs about <tt>--detail-...</tt> options added).
        Also handling of some Inventor models improved &mdash;
        RotationXYZ is handled, some other Inventor fields are parsed
        (and then ignored).
      <li>' . news_a_href_page("kambi_lines", "kambi_lines") . '
        (previously known as <b>kulki</b>) 1.1.0 released &mdash;
        new program name, complete English documentation,
        English help text inside the game. Polish docs removed.
      <li>' . news_a_href_page("lets_take_a_walk", "lets_take_a_walk") . ' 1.1.0
        released &mdash; complete English documentation, F1 shows help text,
        sources contain <i>really</i> all source files &mdash; including
        <tt>devel</tt> subdir with some scripts and Blender, GIMP and Terragen
        data files. Polish docs removed.
      <li>' . news_a_href_page("glcaps", "glcaps") . ' 1.1.0
        released &mdash; complete English documentation. Polish docs removed.
      <li>' . news_a_href_page("glViewImage", "glviewimage") . ' 1.1.3
        released &mdash; complete English documentation, small changes.
      <li>' . news_a_href_page("bezier_curves", "bezier_curves") . ' 1.1.2
        released &mdash; complete English documentation, small changes.
    <li>' . news_a_href_page("rayhunter", "rayhunter") . ' 1.1.0
        released &mdash; complete English documentation, greatly extended
        abilities of <tt>--write-partial-rows</tt> option by <tt>&lt;log-rows-file&gt;</tt>.
        Polish docs removed.
      <li><tt>various_notes_begin.pasdoc</tt> and <tt>gen_light_map.dpr</tt>
        are contained in units sources.
    </ul>

    <p><b>Second update on the same day, October 2, 2005:</b>
    <ul>
      <li>' . news_a_href_page("lets_take_a_walk", "lets_take_a_walk") . ' 1.1.1
        released (only for Linux, other binaries stay 1.1.0) &mdash;
        when using OpenAL sound, sometimes <tt>lets_take_a_walk</tt>
        hanged on exit (i.e. when you pressed Escape or Alt+F4 etc.). Fixed now.
    </ul>

    <p><b>Third update on the same day, October 2, 2005:</b><br>
    (busy day, eh ? :)
    <ul>
      <li>' . news_a_href_page("lets_take_a_walk", "lets_take_a_walk") . '
        updated. Accidentaly 1.1.0 and 1.1.1 packages (binary,
        for all OSes, and source) were uploaded without one texture
        correct, and you will not see cool shadows on the floor.
        They are repackaged now, and all is fixed.
        In other words: if you happened to download
        ' . news_a_href_page("lets_take_a_walk", "lets_take_a_walk") . '
        today, between the hours 0.00 &ndash; 7.00, please download it and install
        once again.
    </ul>
    </div>

  <div class="old_news_item"><p><span class="old_news_date">September 12, 2005</span>
    <ul>
      <li>Units ' . news_a_href_page('sources', 'sources') . ' and
        ' . news_a_href_page('documentation generated by pasdoc', 'reference') . '
        updated (recently implemented
        <a href="http://pasdoc.sourceforge.net/">pasdoc</a> features used
        (e.g. @xxxList tags),
        <a href="' . CURRENT_URL . 'apidoc/html/introduction.html#OpenGLOptimization">
        OpenGL optimization notes</a> are now part of
        documentation parsed by pasdoc and are available for viewing in
        output HTML / pdf docs, some things translated to English,
        various small improvements in sources).
      <li>Small other improvements: better layout of
        ' . news_a_href_page('sources', 'sources') . ' page,
        DBGridExporter has fixed XML export.
      <li>' . news_a_href_page('lets_take_a_walk', 'lets_take_a_walk') . '
        version 1.0.4 release for Linux, fixes a bug (under Linux you had
        to install openal-dev lib, but installing only openal should be sufficient)
    </ul>
    </div>

  <div class="old_news_item"><p><span class="old_news_date">June 07, 2005:</span>
    <ul>
      <li>' . news_a_href_page('Documentation of my sources generated by pasdoc',
        'reference') . ' updated, to reflect many recent improvements done to
        <a href="http://pasdoc.sourceforge.net/">pasdoc</a>.
      <li>' . news_a_href_page('Sources', 'sources') . ' of units
        updated &mdash; small fixes, KambiClassUtils.TTextReader improved
        (removed this "latency" in Readln), it\'s used in pasdoc code now.
    </ul>
    </div>

  <div class="old_news_item"><p><span class="old_news_date">May 21, 2005:</span>
    <ul>
      <li>' . news_a_href_page('Demo of documentation of my sources',
        'reference') . '  added, as generated by
        <a href="http://pasdoc.sourceforge.net/">pasdoc</a>.
        I\'m also proud to announce that I\'m now one of pasdoc\'s developers.

      <li>' . news_a_href_page('My sources', 'sources') . '
        contain now Lazarus package files to easier use my units
        inside <a href="http://www.lazarus.freepascal.org/">Lazarus</a>
        programs.

      <li>Automatic tests of my units (implemented using fpcunit) are published
        and downloadable from ' . news_a_href_page_hashlink('sources page',
        'sources', 'section_sources_test') . '

      <li>KambiClassUtils unit: some important generally-useful
        stream classes implemented:
        TPeekCharStream, TSimplePeekCharStream, TBufferedReadStream.

        <p>All VRML loading code now loads using TPeekCharStream,
        you can always wrap any other TStream inside
        TSimplePeekCharStream or TBufferedReadStream.
        This means that loading VRML from file is both more flexible in source code
        and less memory-consuming at runtime.

        <p>Also ' . news_a_href_page_hashlink(
          'all VRML reading code can read VRML files compressed by gzip.',
          'kambi_vrml_extensions', 'section_ext_gzip') . '

        <p>' . news_a_href_page('view3dscene', 'view3dscene') . '
        updated to version 1.1.2,
        ' . news_a_href_page('rayhunter', 'rayhunter') . '
        updated to version 1.0.1.
        ' . news_a_href_page("Example VRMLs",
        "kambi_vrml_test_suite") . ' updated.

      <li>Some fixes, including serious bigfix to VRMLFlatSceneGL unit
        for SeparateShapeStates optimization (although this accidentaly
        didn\'t affect programs compiled with FPC 1.9.8).
        ' . news_a_href_page('view3dscene', 'view3dscene') . '
        updated to version 1.1.2,
        ' . news_a_href_page('rayhunter', 'rayhunter') . '
        updated to version 1.0.1,
        ' . news_a_href_page('malfunction', 'malfunction') . '
        updated to version 1.0.3,
        ' . news_a_href_page('lets_take_a_walk', 'lets_take_a_walk') . '
        updated to version 1.0.3.

      <li>All new versions are compiled using FPC 2.0.0 (Whohoo !
        Finally new stable version of FPC !).
        Comments on ' . news_a_href_page('sources', 'sources') . '
        page updated.

      <li>You can now use FPC OpenGL bindings (gl, glu, glext units)
        (slightly fixed, patches will be submitted to FPC team)
        instead of my OpenGLh binding.
        Just define USE_GL_GLU_UNITS.
        In the future my OpenGLh unit may be removed, and I\'ll switch to
        always using FPC OpenGL bindings.

      <li>' . news_a_href_page('glplotter', 'glplotter_and_gen_function') . ' updated to
        version 1.1.2: small bugfix: plots with "_" in names
      <li><a href="http://michalis.ii.uni.wroc.pl/~michalis/mandaty.php">mandaty</a> page added.
    </ul>

    <!-- glViewImage sources only updated to 1.1.2, small fixes to sources -->
    </div>

  <div class="old_news_item"><p><span class="old_news_date">March 14, 2005:</span>

  <!--
    /*
    A lot of important things were implemented. Below is only a summary,
    to read full log of changes see < ? php echo
    a_href_page(\'log of changes to these pages\', \'news\') ? >.
    New versions of
     < ?php echo a_href_page(\'view3dscene\', \'view3dscene\'); ? >  (1.1.1),
     < ?php echo a_href_page(\'lets_take_a_walk\', \'lets_take_a_walk\'); ? >  (1.0.2),
     < ?php echo a_href_page(\'malfunction\', \'malfunction\'); ? >  (1.0.2),
     < ?php echo a_href_page(\'glViewImage\', \'glviewimage\'); ? >  (1.1.1),
     < ?php echo a_href_page(\'glplotter\', \'glplotter\'); ? >  (1.1.1),
     < ?php echo a_href_page(\'bezier_curves\', \'bezier_curves\'); ? >  (1.1.1) uploaded.
    <ul>
      <li><p>Many optimizations of OpenGL display (frustum culling,
        with and without the help of octree,
        and many other speed improvements here and there).
        All VRML programs work now faster.

        <p>Added to sources file <tt>units/vrml/opengl/README.optimization_notes</tt>
        that describes how current optimization works, what are the possible
        drawbacks and what are the possible alternatives (and what
        drawbacks are hidden in those alternatives :).
        In case you\'re interested how it works but you don\'t want to download
        my whole sources, you can read this document
        <a href=\'src/pascal/README.optimization_notes\'>online</a>.
      <li>Smoother reaction to collision in
        < ?php echo a_href_page(\'view3dscene\', \'view3dscene\'); ? >  and
        < ?php echo a_href_page(\'lets_take_a_walk\', \'lets_take_a_walk\'); ? >.
      <li>Mnemonics for GLWindow menus implemented.
    </ul>
    -->

    <ul>
      <li>New versions of ' . news_a_href_page('view3dscene', 'view3dscene') . '
        (1.1.1) and ' . news_a_href_page('lets_take_a_walk', 'lets_take_a_walk') . '
        (1.0.2) allow somewhat more smooth camera moving:
        when you try to step into the wall (or floor, or ceil, whatever),
        your move is not completely blocked. Instead you are allowed to slowly
        move alongside the wall.

        <p><small>This is implemented by new interface to
        TMatrixWalker.OnMoveAllowed and new method TVRMLOctree.MoveAllowed.</small>

      <li>I removed units gtkglext and gdkglext from my sources,
        they are now incorporated into FPC source tree.
        Also some of my fixes to gtk2 bindings are submitted to FPC sources,
        that\'s why I decided to remove gtkglext and gdkglext units
        from my sources, since you would have to download newest
        gtk2 bindings from FPC cvs anyway.

      <li><p>First part of optimizing OpenGL display using frustum culling
        done: frustum culling without the help of octree done.

        <p>User-visible changes: added <tt>--renderer-optimization</tt>
        parameter for ' . news_a_href_page('view3dscene', 'view3dscene') . ', see
        ' . news_a_href_page("view3dscene", "view3dscene") . '
        page for docs of this parameter.
        <!-- Minor
        view3dscene\'s commands "Print current camera node",
        "Print raytracer command-line" work in Examine navigation mode. -->
        New ' . news_a_href_page('view3dscene', 'view3dscene') . '
        menu commands "View|Show in Examine mode camera frustum",
        "Console|Print current camera frustum".

        <p><small>Sources changes:
        VRMLFlatSceneGL unit allows new optimization method:
        roSeparateShapeStates.
        VectorMath and MatrixNavigation: done routines to calculate
        frustum\'s planes, and calculate frustum\'s geometry,
        and check whether frustum collides with sphere and TBox3d.
        TVRMLFlatSceneGL.RenderFrustum done.</small>

      <li><p>Second part of optimizing OpenGL display using frustum culling
        with the help of octree: done.

        <p><small>Done second octree based on scene ShapeStates.
        ' . news_a_href_page('view3dscene', 'view3dscene') . '
        creates it, and can display it\'s statistics.
        TOctree.ItemsInNonLeafNodes property added to allow
        octree nodes to store all cummulated items of their children.
        TOctree.EnumerateCollidingOctreeItems implemented and
        TVRMLFlatSceneGL.RenderFrustumOctree implemented.</small>

      <li><p>Added to sources file <tt>units/vrml/opengl/README.optimization_notes</tt>
        that describes how current optimization works, what are the possible
        drawbacks and what are the possible alternatives (and what
        drawbacks are hidden in those alternatives :).
        In case you\'re interested how it works but you don\'t want to download
        my whole sources, you can read this document
        <a href="' . CURRENT_URL . 'apidoc/html/introduction.html#OpenGLOptimization">
        online</a>.

      <li><p>gprof rulez &mdash; small bug that was harmless but was
        causing a lot of slowdown in TVRMLFlatScene.ValidateFog
        (combined with roSeparateShapeStates) fixed.
        Also problem in VRMLFlatSceneGL with GL_COMPILE_AND_EXECUTE solved.
        Also problem with updating Caption too often (this caused
        some noticeable slowdown on XWindows on my system).

      <li><p>Example program <tt>units/vrml/opengl/simpleViewModel_2.dpr</tt>
        added.

      <!-- /* echo a_href_page(\'malfunction\', \'malfunction\'); (1.0.2):
        small code adjustments to be compileable. */ -->

      <li><p>Mnemonics for GLWindow menus implemented.
        ' . news_a_href_page('view3dscene', 'view3dscene') . ' (1.1.1),
        ' . news_a_href_page('glViewImage', 'glviewimage') . ' (1.1.1),
        ' . news_a_href_page('glplotter', 'glplotter_and_gen_function') . ' (1.1.1),
        ' . news_a_href_page('bezier_curves', 'bezier_curves') . ' (1.1.1)
        all updated with mnemonics.
    </ul>
    </div>

  <div class="old_news_item"><p><span class="old_news_date">February 28, 2005:</span>
    <ul>
      <li>Finally, GLWindow unit may be based on GTK 2 instead of that old GTK 1.
        Besides obvious usability benefits of using GTK 2, which is just
        better than GTK 1, also fullscreen mode is now better (things like
        gnome-panel don\'t cover your window).

        <p>' . news_a_href_page('view3dscene', 'view3dscene') . ',
        ' . news_a_href_page('glViewImage', 'glviewimage') . ',
        ' . news_a_href_page('glplotter', 'glplotter_and_gen_function') . ',
        ' . news_a_href_page('bezier_curves', 'bezier_curves') . '
        are updated (minor version number++, to 1.1.0,
        Linux/FreeBSD users are encouraged to upgrade).

        <p>Inside sources, opengl/gtk/gtkglext/ directory is created with
        GdkGLExt and GtkGLExt units.

    <!-- Minor:
      Some more things that were only for FPC 1.0.10 are removed from sources.
      Some more things translated to English, as usual.
    -->

      <li>glWinEvents, menu_test_alternative, test_glwindow_gtk_mix
        added to units/opengl/examples/

      <li>All www pages marked in footer as licensed on GNU GPL,
        added page explaining
        <a href="http://michalis.ii.uni.wroc.pl/~michalis/why_not_gfdl.php">why
        I do not use GNU FDL</a>.

      <li>F5 is now the standard key shortcut for save screen
        (F10 was conflicting with standard "drop menu" key on gnome and win32),
        changed ' . news_a_href_page('view3dscene', 'view3dscene') . ',
        ' . news_a_href_page('malfunction', 'malfunction') . ',
        ' . news_a_href_page('lets_take_a_walk', 'lets_take_a_walk') . ',
        ' . news_a_href_page('glplotter', 'glplotter_and_gen_function') . ',
        ' . news_a_href_page('bezier_curves', 'bezier_curves') . '.
        ' . news_a_href_page('view3dscene', 'view3dscene') . ' and
        ' . news_a_href_page('glplotter', 'glplotter_and_gen_function') . '
        display FileDialog before saving screen.

        <!-- malfunction, lets_take_a_walk: version release++ to 1.0.1 -->

    <!-- jamy i nory and bad blaster moved to the bottom of index page,
         since they are rather uninteresting and not maintained anymore -->

      <li>Switching to FPC 1.9.8. Some archives on these pages still remain with
        binaries compiled with FPC 1.9.6, but I will replace them
        at their next update. In any case, you should use FPC 1.9.8
        if you\'re going to compile my code.
    </ul>
    </div>

  <div class="old_news_item"><p><span class="old_news_date">February 3, 2005:</span>
    <ul>
      <li><p>Complete rework of Images unit interface.
        Now it has object-oriented interface, much safer and cleaner.
        Unfortunately compatibility with previous versions is broken.

        <p><b>Details</b>:
        Records TImageRec, TRGBImageRec, TAlphaImageRec are now replaced
        with classes TImage, TRGBImage, TAlphaImage (and there\'s also TRGBEImage
        class).

        <p>No functionality is lost, but now using these classes is
        more straightforward,
        no longer need to maintain "dummy" conversion routines
        ImageRec(To|From)(RGB|Alpha).
        Also now you can use Images unit to define new TImage descendant.
        Also many things are now safer and checked by compiler at compile-time.

        <p>Also many docs updated and translated to English in Images unit.

        <p>Sources of most programs needed to be changed accordingly.
        Changed: ' . news_a_href_page('view3dscene', 'view3dscene') . ',
        ' . news_a_href_page('glViewImage', 'glviewimage') . ',
        ' . news_a_href_page('rayhunter', 'rayhunter') . ',
        ' . news_a_href_page('malfunction', 'malfunction') . ',
        ' . news_a_href_page('kulki', 'kambi_lines') . ',
        ' . news_a_href_page('lets_take_a_walk', 'lets_take_a_walk') . ',
        ' . news_a_href_page('bezier_curves', 'bezier_curves') . '.

      <li><p>Improvement in KambiPng/KambiZlib units:

        <p><b>Details</b>:
        Now programs using these units (e.g. indirectly by using Images unit)
        do not require libpng+zlib to be installed on user system.
        <ol>
          <li>view3dscene now runs without libpng and/or zlib installed.
            When opening kings_head.wrl, it displays a warning:
            <i>view3dscene: WARNING: Exception ELibPngNotAvailable occurred when
            trying to load texture from filename textures/crown.png :
            LibPng is not available</i>
            and loads kings_head.wrl correctly (well, without texture
            textures/crown.png).
          <li>No need to define any symbol NOT_USE_LIBPNG at compilation of
            programs that must not depend on libpng+zlib, but must depend on Images
            unit (concerns glcaps and glcaps_glut).
        </ol>

      <li><p>GTK GLWindow: key shortcuts in menus are displayed and handled
        entirely by GTK, not by some hacks in GLWindow unit.

        <p>Also WinAPI key shortcuts to menus are now displayed as they should
        (justified to the right).

      <li><p>key shortcuts changed to conform to be more standard (I\'m trying
        to follow GNOME HIG, although I know that my programs are pretty
        far from it right now), and also to not cause problems as GTK 1
        or GTK 2 menu item shortcuts:
        <ul>
          <li>glViewImage, glplotter: help: F1 (was: \'?\'),
          <li>glViewImage, lets_take_a_walk, view3dscene, glplotter:
            FullScreen on/off: Ctrl+F (was: Tab)
          <li>bezier_curves: Delete selected point: d (was: delete)
            Nothing selected: n (was: backspace).
            (I think that backspace or delete or Ctrl+Shift+A would be a better
            shortcut, but backspace or delete is impossible with GTK 1
            and Ctrl+Shift+A is impossible because of temporary lacks in GLWindow
            interface...)
        </ul>

      <li><p>All my Pascal programs get a version number.
        Existing programs on these pages are initially marked as version 1.0.0.
        Added ' . news_a_href_page('page describing my versioning scheme',
        'versioning') . '.

        <p>All programs with version number accept <tt>-v</tt> (or <tt>--version</tt>)
        command-line parameter to display version number.
        Page with ' . news_a_href_page(
        'some notes about parameters understood by my programs',
        'common_options') . ' updated.

      <li><p>From now on, all FPC programs on these pages will be compiled
        with FPC 1.9.6. Compatibility with FPC 1.0.10 is dropped,
        and I will do not even guarantee that my programs compile with FPC 1.9.4.
        So all programs are recompiled and all sources updated,
        ' . news_a_href_page('sources','sources') . '
        page is also updated.

      <li><p>Published imageToPas in <tt>units/images/tools/</tt>.

      <li><p>Small example of MathExprParser unit in units/base/examples/kambi_calc.dpr.

      <li><p>Polish version of page with ' . news_a_href_page('some notes about parameters understood by my programs',
        'opengl_options') . ' is removed. Only English version will
        be maintained from now on.

      <li><p>glViewImage has new Edit menu with some simple commands that change
        viewed image. This was done mainly to basically test that these functions
        work, but may be useful anyhow.

      <li><p>Fixed some problems with using <tt>--fullscreen-custom</tt> under Win32.

      <li><p>Removed from sources many files that were needed only for FPC 1.0.10:
        randomconf.inc, mtrand.pas, 10 files *_defpars.inc

      <li><p>UNIX (Linux, FreeBSD) versions of malfunction, kulki,
        lets_take_a_walk again have ability to change screen resolution
        (<tt>--fullscreen-custom</tt> parameter)
        that was not available due to bug in FPC 1.0.10.

      <li><p>Some usability problems with ' . news_a_href_page('kulki',
        'kambi_lines') . ' solved.
    </ul>
    </div>

  <div class="old_news_item"><p><span class="old_news_date">January 12, 2005:</span>
    Added to ' . news_a_href_page('sources', 'sources') . '
    nice example programs that demonstrate some higher-level
    functionality of my units:
    <ul>
      <li><tt>units/opengl/examples/menuTest.dpr</tt> (GLWindow with menu)
      <li><tt>units/vrml/opengl/examples/simpleViewModel.dpr</tt>
        (simple demo of loading and rendering VRML/3DS models and
        allowing user to walk in them; something like extremely-simplified
        view3dscene)
      <li><tt>units/vrml/examples/many2vrml.dpr</tt> (converting 3DS and others
        to VRML)
    </ul>
    Also ' . news_a_href_page('sources', 'sources') . ' page updated with
    comments about FPC 1.9.6 version. Also some small changes in sources,
    as usual. Added \'xxx.dylib\' library names for Darwin.
    </div>

  <div class="old_news_item"><p><span class="old_news_date">December 10, 2004:</span>
    ' . news_a_href_page('Sources of units and view3dscene', 'sources') . '
    updated: units and view3dscene compile with FPC 1.9.5 from CVS
    from 2004-12-07 (at least under Linux), <tt>units/base/examples/</tt>
    subdirectory with two small example programs.
    </div>

  <div class="old_news_item"><p><span class="old_news_date">December 5, 2004:</span>
    ' . news_a_href_page('Sources of units', 'sources') . '
     updated to commit many improvements to docs
    (some translations to English and some preparations to generate
    nice docs with pasdoc). No big changes.

    <p>Note: Don\'t expect any new things to happen on these pages
    this month (I\'m busy in some commercial project since some time,
    and I probably won\'t have time this month).
    However expect many work to happen here next year.
    </div>

  <div class="old_news_item"><p><span class="old_news_date">August 23, 2004:</span>
    <ul>
      <li>I ported all programs (except edytorek) to FreeBSD.
        You can download FreeBSD releases (tar.gz archives) from pages
        of appropriate programs.
      <li>All ' . news_a_href_page('Pascal sources', 'sources') . ' updated,
        and the page ' . news_a_href_page('Pascal sources', 'sources') . '
        itself is also updated (more detailed and up-to-date info about FPC
        versions).
      <li>Also, some small updates and bug-fixes for Linux and Windows.
        All programs updated.
        <!--
          Linux: in OpenGL small update to improve some error
            message under newer NVidia drivers,
          Linux: ProcTimer fixed,
          all: DOC/ subdirectory in archives contains documentation
        -->
      <!-- gen_pole_kier removed -->
      <!--
      /*

      DONE:
      release version of every Pascal program compiled and tested
        for FreeBSD and Windows and Linux
      check Linux rayhunter - - was it working before fixing ProcTimer ?
        (probably too late - - it seems it was already recompiled with
        ProcTimer fixed, nevermind)
      check for broken links, check for valid html
      uaktualnic wszystkie archives programow Pascalowych
      uaktualnic wszystkie sources
      upload *.php, everything in sources and archives
      remove from server everything for gen_pole_kier

      ProcTimer corrected under FreeBSD,
      rayhunter updated,
      units updated
      */
      -->
    </ul>
    </div>

  <div class="old_news_item"><p>(August 7: another update of units\' sources, small changes)
        <!--
          update malfunction-src (progress interface changed),
          update units-src
          ( some improvements to work with Unix/BaseUnix units,
            KambiUtils.ProcTimer fixed,
            some functionality from KambiUtils moved to new EnumerateFiles unit,
            OpenGLh corrected to write appropriate TLS warning
              even with new nVidia drivers,
            EXTENDED_EQUALS_DOUBLE )
        --></div>

  <div class="old_news_item"><p><span class="old_news_date">August 2, 2004:</span>
    <ul>
      <li>Updated ' . news_a_href_page('sources', 'sources') . ' of
        standard units, view3dscene and rayhunter. Using correct FPC UNIX
        RTL (instead of Libc always) with FPC 1.9.x basically done, everything
        seems quite ready to be ported to other UNIX-like systems,
        many comments translated to English and, as always, some random small
        improvements.
    </ul></div>

  <div class="old_news_item"><p><span class="old_news_date">31 July 2004:</span>
    <ul>
      <li>Various small internal changes/improvements in
        ' . news_a_href_page('sources', 'sources') . ', ProgressUnit improved.
      <li>Polish versions of glViewImage and view3dscene docs removed,
        they were too outdated.
    </ul>
    <!-- (on HTML pages: /s/~/$HOME/, consequently) --></div>

  <div class="old_news_item"><p><span class="old_news_date">27 June 2004:</span>
    <ul>
      <li>' . news_a_href_page('view3dscene', 'view3dscene') . ' updated:
        "Configure scene loading" submenu
        (it\'s just a GUI for <tt>--scene-changes-xxx</tt> command-line params)
      <li>Some small updates: to HTML pages,
        to ' . news_a_href_page('malfunction', 'malfunction') . '
        under Linux (no GTK dependency),
        to ' . news_a_href_page('lets_take_a_walk', 'lets_take_a_walk') . '
        under Windows (default device = DirectSound3D),
        to ' . news_a_href_page('rayhunter', 'rayhunter') . '
        (allowed warnings while loading scene)
      <li>Many small improvements in
        ' . news_a_href_page('sources', 'sources') . ',
        among other things units/Makefile supports separate compilation
        of units and things are now more prepared
        for pasdoc. Be ready for more sources updates in the near future &mdash;
        I want to translate many things to English (both user docs
        for some programs and comments in sources) and I want to generate
        nice sources documentation using pasdoc.
    </ul>
    </div>

  <div class="old_news_item"><p><span class="old_news_date">29 May 2004:</span>
    <ul>
      <li>' . news_a_href_page('view3dscene',  'view3dscene') . ' updated:
        <ul>
          <li>Big improvement: "Open File" menu item (key shortcut Ctrl+O),
            i.e. finally changing loaded scene at runtime is fully allowed.
            This also means that now you don\'t have to specify a filename
            to open at command-line.
          <li>Fixed treating of material transparency in OpenGL rendering,
            now you can really see that various values for transparency
            (like 0.1, 0.5, 0.9) make a difference.
            ' . news_a_href_page("Example VRMLs",
              "kambi_vrml_test_suite") . ' extended to confirm this:
            new test scene transparent_materials.wrl.
          <li>FPS timing after the very 1st frame fixed.
          <li>view3dscene now honours AsciiText.justification value,
            text.wrl file (in ' . news_a_href_page("Example VRMLs",
              "kambi_vrml_test_suite") . ') updated to demonstrate this.
          <li>You can now change color of background in view3dscene using
            comfortable dialog box. (GTK dialog box under Linux or WinAPI dialog box).
        </ul>

      <!--
        lets_take_a_walk updated (to get "treating of material transparency" fix)
        rayhunter updated (to get "SFString not enclosed in quotes" fix)
        src of malfunction, kulki, glViewImage, glcaps updated
          (because in the past I completely forgot about GPL headers
          in those sources, now it\'s fixed)
      -->
    </ul>
    </div>

  <div class="old_news_item"><p><span class="old_news_date">25 May 2004:</span>
    <ul>
      <li>bezier_curves updated: smooth interpolated curves
        (smoothly connected Bezier curves) implemented,
        changing colors (using color dialog box) implemented.
        <!--
        <li>fixed some visual unpleasance when opening incorrect files from
          bezier_curves in Linux
        -->
    </ul>
    </div>

  <div class="old_news_item"><p><span class="old_news_date">20 May 2004:</span>
    <ul>
      <li>' . news_a_href_page("view3dscene", "view3dscene") . ' updated:
        <ul>
          <li>small updates in interface ([l] key restored,
            \'...\' added to some menu\'s Caption),
          <li>view3dscene works with scenes with BoundingBox = EmptyBox3d,
          <li>default texture minification method is now LINEAR_MIPMAP_LINEAR
            (best looking),
          <li>Added "When picking with left mouse button, show ..." menu
            to control amount of information shown when picking objects
            with left mouse button, this allows showing some extra info
            aobut materials, lights and shadows, thus making picking
            objects with mouse more usable.
          <li>Small thing in VRML parsing corrected: SFString fields
            not enclosed in double quotes are now parsed correctly.
            <!-- All VRML files generated by Blender should now load correctly. -->
        </ul>
      <li>' . news_a_href_page("Example VRMLs",
        "kambi_vrml_test_suite") . '
        improved: added new tests (empty_xxx.wrl),
        filenames reorganized, model castle.wrl <i>greatly</i> improved
        (with nice textures !).
      <li>' . news_a_href_page("malfunction", "malfunction") . ' updated:
        level "sunny day" completely redesigned,
        rest of levels corrected. I\'m still not satisfied with these levels,
        but at least now they are slightly better.
    </ul>
    </div>

  <div class="old_news_item"><p><span class="old_news_date">8th of May, 2004</span>
    <ul>
      <li>Finally ! ' . news_a_href_page(
        'Sources for Pascal programs are published !', 'sources') . '
        Of course license is GNU GPL.
        On 10th of May I added sources of malfunction and kulki.
        This means that <i>all</i> programs available on these pages,
        with the exception of edytorek, are distributed with sources.
      <li>New program: ' . news_a_href_page('bezier_curves', 'bezier_curves') . '
      <li>Default main page is in English now. Alternative Polish version
        is still available.
    </ul>
    </div>

<!--
  27.04: bezier_curves "raw" wrzucone
-->

  <div class="old_news_item"><p><span class="old_news_date">26th of April, 2004</span>
    <ul>
      <li>UI improved:
        ' . news_a_href_page('glViewImage', 'glviewimage') . ' and
        ' . news_a_href_page('view3dscene', 'view3dscene') . '
        now use GTK / Windows Open/Save file dialogs.
        "Checked" menu items made possible,
        ' . news_a_href_page('view3dscene', 'view3dscene') . '
        and ' . news_a_href_page('glplotter', 'glplotter_and_gen_function') . '
        improved.
        ' . news_a_href_page('view3dscene', 'view3dscene') . '
        uses special menus while raytracing.
      <li>Some problems under Windows with some OpenGLs resolved
        (somewhat random "Invalid floating point operation" errros)
      <li>' . news_a_href_page('glViewImage', 'glviewimage') . ' :
        Ctrl+O adds images to images list,
        menu "Image list" is updated at runtime,
        english docs improved, polish docs dropped.
      <!-- glcaps with - -single and - -double updated -->
      <!-- suma:  glViewImage, view3dscene, glplotter, gen_pole_kier,
           glcaps, glcaps_glut updated -->
    </ul>
    </div>

<!--
14th of April, small update, view3dscene now uses legally free
Bistream Vera fonts. view3dscene archives updated,  rayhunter-src sources
remade.
-->

  <div class="old_news_item"><p><span class="old_news_date">13th of April, 2004</span>
    <ul>
      <li>On 10.04 my small page joined strike against software patents in Europe.
      <li>' . news_a_href_page('glViewImage', 'glviewimage') . ',
          ' . news_a_href_page('glplotter', 'glplotter_and_gen_function') . '
           updated: Linux (GTK) versions stabilized.
      <li>' . news_a_href_page('view3dscene', 'view3dscene') . '
        updated:
        <p>Full English docs finally available.
        Maintenance of Polish docs dropped. Many general reorganizations in docs.

        <p>Many changes in user interface in view3dscene too: some rarely used
        key bindings removed (m, g, n), many parts of menu extended to something
        more comfortable, "lights kind" replaced with 3 separate settings:
        "light calculate", "head light", "use scene lights".
        --lights-kind parameter dropped, new --light-calculate (should be much more
        useful) parameter added.

        <p>Linux (GTK) version stabilized.
      <li>Last but not least: first, unofficial release of sources of
        ' . news_a_href_page('rayhunter', 'rayhunter') . '
        is available here (<i>link not available anymore</i>).
        Currently everything
        is just packaged in one tar.gz file,
        rayhunter-src.tar.gz (<i>link not available anymore</i>). Of course it\'s GNU GPL-licensed.

        <p>You need <a href="http://www.freepascal.org">FreePascal Compiler</a>
        to compile this. You also need to slightly modify sources of FPC.
        Here are diffs for FPC 1.0.10 and FPC 1.9.3 (<i>link not available anymore</i>).
        Note that FPC 1.9.3 (downloadable
        from FreePascal CVS server) requires considerably lesser amount of
        "hacking" to compile rayhunter. If you use FPC 1.9.3, you will only have to
        apply changes to packages/libc unit under Linux, and under Windows
        everything should compile with unmodified FPC 1.9.3 version.
        While with FPC 1.0.10 there are many more patches
        and you will have to add some additional units.
        So I strongly suggest you to use FPC 1.9.3.
    </ul>
    </div>

  <div class="old_news_item"><p><span class="old_news_date">18th of march, 2004</span>: I updated ' . news_a_href_page("view3dscene",
    "view3dscene") . ' and ' . news_a_href_page("glViewImage", "glviewimage") . '.
    Now both programs have a useful menu bar, under Windows and Linux.
    Under Linux this requires installation of GTK 1.x and gtkglarea libraries.
    This is some attempt to make user interface of those programs a little
    more friendly. I\'m curious about your observations about this improvement &mdash;
    how do you like it, how does it work under various Linux and Windows
    versions etc.
    </div>

<p>I started to maintain this update log at 18th march, 2004.</p>
    '),
  );

/* --------------------------------------------------------------------------- */

foreach ($news as &$log_entry)
{
  $log_entry['pubDate'] = date_timestamp(
    $log_entry['year'],
    $log_entry['month'],
    $log_entry['day'],
    (isset($log_entry['hour'])   ? $log_entry['hour']   : 0),
    (isset($log_entry['minute']) ? $log_entry['minute'] : 0));

  if (!isset($log_entry['guid']))
    $log_entry['guid'] =
      $log_entry['year'] . '-' .
      $log_entry['month'] . '-' .
      $log_entry['day'] . '-' .
      /* For safety and to make guid look nicer, remove special characters.
         Not all these replacements are really necessary, only <> and &
         to avoid breaking XML. guid is used in both RSS XML and in HTML. */
      strtr(strtolower($log_entry['title']), ' &;,:*/()<>+', '____________');

  $log_entry['id'] = $log_entry['guid'];

  $log_entry['link'] = CURRENT_URL . 'news.php?id=' . $log_entry['id'];
}
unset($log_entry);

define('TEASER_DELIMITER_BEGIN', '<!-- teaser ');
define('TEASER_DELIMITER_END', '-->');

function vrmlengine_news_date_long($news_item)
{
  $month_names = array(
    1 => 'January',
    2 => 'February',
    3 => 'March',
    4 => 'April',
    5 => 'May',
    6 => 'June',
    7 => 'July',
    8 => 'August',
    9 => 'September',
    10 => 'October',
    11 => 'November',
    12 => 'December'
  );
  return $month_names[$news_item['month']] . ' ' .
    $news_item['day'] . ', ' .
    $news_item['year'];
}

function news_to_html($news_item, $full_description = true, $link_to_self = false)
{
  if ($full_description)
  {
    $description = $news_item['description'];
  } else
  if ($news_item['short_description'] != '')
  {
    $description = $news_item['short_description'];
  } else
  {
    $description = $news_item['description'];
    $teaser_delimiter = strpos($description, TEASER_DELIMITER_BEGIN);
    if ($teaser_delimiter !== FALSE)
    {
      $teaser_delimiter_end = strpos($description, TEASER_DELIMITER_END);

      $teaser_closing_str = substr($description,
        $teaser_delimiter + strlen(TEASER_DELIMITER_BEGIN),
        $teaser_delimiter_end -
        ($teaser_delimiter + strlen(TEASER_DELIMITER_BEGIN)));

      $description = substr($description, 0, $teaser_delimiter) .
        '<p><a href="' . CURRENT_URL . 'news.php?item=' .
        $news_item['id'] . '">[read more]</a></p>' .
        $teaser_closing_str;
    }
  }

  $title = $news_item['title'];
  if ($link_to_self)
    $title = '<a href="' . CURRENT_URL . 'news.php?item=' .
      $news_item['id'] . '">' . $title . '</a>';
  $title = '<span class="news_title">' . $title . '</span>';

  return '<p>' . $title . '<br/><span class="news_date">(' .
    vrmlengine_news_date_long($news_item) . ')</span></p>' .
    $description;
}

function last_news_to_html($full_description = true)
{
  global $news;

  return news_to_html($news[0], $full_description, /* link to self */ true);
}

?>
