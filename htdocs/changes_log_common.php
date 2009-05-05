<?php /* -*- mode: php -*- */
  /* This is common content for both making RSS feed of changes_log,
     and HTML version. You must have already common_set_page_functions
     done before requiring this !
  */

/* Beware that making timestamps using PHP is broken:

     function date_timestamp($year, $month, $day)
     {
       return gmmktime(0, 0, 0, $month, $day, $year);
     }

     echo date_timestamp(2007, 07, 25) . '<br/>';
     echo date_timestamp(2007, 08,  1);

   results in
     1185321600
     1164931200

   which is obviously wrong, since the 2nd timestamp is smaller than the
   1st one... This is with PHP 5.2.3-1+b1 from Debian package.
   For safety, I'll just generate timestamps with ../scripts/date_to_timestamp.sh.
   This is correct:
     $ ./date_to_timestamp.sh '2007-07-25'
     1185321600 // ok, just like PHP gmmktime
     $ ./date_to_timestamp.sh '2007-08-01'
     1185926400 // ok, larger than previous one, different than PHP gmmktime
*/

function this_a_href_page($title, $page_name)
{
  /* For RSS feed, URLs must be absolute (some RSS readers,
     like Google RSS on main page, don't handle relative URLs as they
     should. And indeed, no standard guarantees that relative URLs
     in RSS would be handled OK). */
  return '<a href="http://vrmlengine.sourceforge.net/' . $page_name .
    '.php">' . $title . '</a>';
}

  /* This an array of changes_log entries.
     It is in format accepted by rss_generator class, but also has some
     extras for changes_log_to_html:
     - year, month, day fields: this must always match pubDate timestamp

     They must be ordered from the newest to the oldest.
     While it doesn't matter for RSS (feed will be sorted anyway by news
     reader), my HTML converting code depends on it (first feed is the "latest
     update", and feeds are presented in given order on changes_log page).
  */

  $changes_log = array(

/* --------------------------------------------------------------------------- */

    array('title' => 'News from SVN - 3D textures, shadow maps, hardware occlusion query and more',
          'year' => 2009,
          'month' => 5,
          'day' => 5,
          'pubDate' => /* date_to_timestamp.sh '2009-05-05' */ 1241524800,
          'guid' => '2009-05-05',
          'link' => 'http://vrmlengine.sourceforge.net/',
          'short_description' => '',
          'description' => '
<table align="right">
  <tr><td>
    <a href="http://vrmlengine.sourceforge.net/images/progs_demo/original_size/trees_river_shadow_maps.png">
      <img align="right" src="http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/trees_river_shadow_maps.png"
      alt="Shadow maps"
      title="Shadow maps"
    /></a>
  </td>
  <td>
    <a href="http://vrmlengine.sourceforge.net/images/progs_demo/original_size/tex3d_smoke.png">
      <img align="right" src="http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/tex3d_smoke.png"
      alt="Fog from 3D noise"
      title="Fog from 3D noise"
    /></a>
  </td></tr>
  <tr><td>
    <a href="http://vrmlengine.sourceforge.net/images/progs_demo/original_size/anisotropic_demo.png">
      <img align="right" src="http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/anisotropic_demo.png"
      alt="Demo how anisotropic filtering helps"
      title="Demo how anisotropic filtering helps"
    /></a>
  </td>
  <td>
    <a href="http://vrmlengine.sourceforge.net/images/progs_demo/original_size/oq_demo.png">
      <img align="right" src="http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/oq_demo.png"
      alt="Occlusion query optimizing city view"
      title="Occlusion query optimizing city view"
    /></a>
  </td></tr>
  <tr><td>
    <a href="http://vrmlengine.sourceforge.net/images/progs_demo/original_size/water_reflections.png">
      <img align="right" src="http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/water_reflections.png"
      alt="Water reflections by optimizeed GeneratedCubeMapTexture"
      title="Water reflections by optimizeed GeneratedCubeMapTexture"
    /></a>
  </td></tr>
</table>

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
          'pubDate' => /* date_to_timestamp.sh '2009-04-10' */ 1239364800,
          'guid' => '2009-04-10',
          'link' => 'http://vrmlengine.sourceforge.net/',
          'short_description' => '',
          'description' => '
<table align="right">
  <tr><td>
    <a href="http://vrmlengine.sourceforge.net/images/progs_demo/original_size/cubemap_teapot.png">
      <img align="right" src="http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/cubemap_teapot.png"
      alt="Teapot with cube map reflections"
      title="Teapot with cube map reflections"
    /></a>
  </td></tr>
</table>

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
          'pubDate' => /* date_to_timestamp.sh '2009-01-24' */ 1232798400,
          'guid' => '2009-01-24',
          'link' => 'http://vrmlengine.sourceforge.net/',
          'short_description' => '',
          'description' => '

<table align="right">
  <tr><td>
    <a href="http://vrmlengine.sourceforge.net/images/progs_demo/original_size/dyn_ao_chinchilla.png">
      <img align="right" src="http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/dyn_ao_chinchilla.png"
      alt="Chinchilla with Dynamic Ambient Occlusion"
      title="Chinchilla with Dynamic Ambient Occlusion"
    /></a>
  </td>

  <td>
    <a href="http://vrmlengine.sourceforge.net/images/progs_demo/original_size/dyn_ao_chinchilla_elements.png">
      <img align="right" src="http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/dyn_ao_chinchilla_elements.png"
      alt="Chinchilla elements used for Dynamic Ambient Occlusion"
      title="Chinchilla elements used for Dynamic Ambient Occlusion"
    /></a>
  </td></tr>

  <tr><td>
    <a href="http://vrmlengine.sourceforge.net/images/progs_demo/original_size/dyn_ao_peach.png">
      <img align="right" src="http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/dyn_ao_peach.png"
      alt="Peach with Dynamic Ambient Occlusion"
      title="Peach with Dynamic Ambient Occlusion"
    /></a>
  </td>

  <td>
    <a href="http://vrmlengine.sourceforge.net/images/progs_demo/original_size/sf_1.png">
      <img align="right" src="http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/sf_1.png"
      alt="Shadow Fields screenshot 1"
      title="Shadow Fields screenshot 1"
    /></a>
  </td></tr>

  <tr><td>
    <a href="http://vrmlengine.sourceforge.net/images/progs_demo/original_size/sf_2.png">
      <img align="right" src="http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/sf_2.png"
      alt="Shadow Fields screenshot 2"
      title="Shadow Fields screenshot 2"
    /></a>
  </td>

  <td>
    <a href="http://vrmlengine.sourceforge.net/images/progs_demo/original_size/sf_3.png">
      <img align="right" src="http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/sf_3.png"
      alt="Shadow Fields screenshot 3"
      title="Shadow Fields screenshot 3"
    /></a>
  </td></tr>
</table>

<p>This week I implemented a demo of <a href="http://http.developer.nvidia.com/GPUGems2/gpugems2_chapter14.html">Dynamic Ambient Occlusion</a> using our engine.</p>

<p>In related news, last month I also implemented a demo of <a href="http://www.kunzhou.net/#shadow-field">Shadow Fields</a>. (I forgot to brag about it earlier, so I\'m doing it now :) ).</p>

<p>An extremely short summary: both techniques strive to make low-frequency soft shadows (in other words, the kind of shadows you usually see in the Real World) in dynamic 3D environment.</p>

<p>For now, they are just implemented as demos, and are not easily available for non-programmers. You have to actually get the source and compile some example programs to try out this stuff. (Although I think I\'ll make at least dynamic ambient occlusion available as an easy option inside view3dscene in the future.) The full source code, and example models, are available in SVN, naturally. Simple instructions:</p>

<pre>
$ svn checkout https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/kambi_vrml_game_engine/

$ cd kambi_vrml_game_engine/3dmodels.gl/examples/shadow_fields
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
          'pubDate' => /* date_to_timestamp.sh '2009-01-03' */ 1230984000,
          'guid' => '2009-01-03',
          'link' => 'http://vrmlengine.sourceforge.net/',
          'short_description' => '',
          'description' => "

<p>" . this_a_href_page('view3dscene 3.3', 'view3dscene') . " is released,
just a mixture of various new features, optimizations and fixes.
Traditionally, " . this_a_href_page('underlying
Kambi VRML game engine 1.7.0', 'kambi_vrml_game_engine') . " is released along.
Changes:</p>

<table align=\"right\">
  <tr><td>
    <a href=\"http://vrmlengine.sourceforge.net/images/progs_demo/original_size/apple_lods.png\">
      <img align=\"right\" src=\"http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/apple_lods.png\"
      alt=\"Apple model with various levels of detail\"
      title=\"Apple model with various levels of detail\"
    /></a>
  </td></tr>
</table>

<ul>
  <li><b>LOD (level-of-detail)</b> node proper handling.</li>

  <li><b>Collision.proxy</b> handling (very handy, allows you to make non-collidable but visible geometry, or approximate complex geometry with simpler for collision detection).</li>

  <li><a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_octree_properties\">KambiOctreeProperties, an extensions to specify octree limits for your scene</a>. <a href=\"" . CURRENT_URL . "vrml_engine_doc/output/xsl/html/section.octrees_dynamic.html\">Section \"Octrees for dynamic worlds\" added to the documentation</a>, to explain how octree works since 1.6.0 version. The shape octree was speed up by mailboxes.</li>

  <li>Various workarounds for <a href=\"http://mesa3d.org/\">Mesa</a> bugs (in particular on Intel GPUs) and Mesa detection improved. This should significantly improve stability for Unix users with cheaper graphic cards. Because of this, also " . this_a_href_page('castle 0.8.3', 'castle') . " and " . this_a_href_page('glcaps 1.1.4', 'glcaps') . " are released, to get these fixes too.</li>

  <li>Various frustum culling optimizations.</li>

  <li>Small improvements in the view3dscene interface: blend status text, a shapes count fix, and keeping the selected triangle when transforming shape.</li>

  <li>The path tracer honors VRML &gt;= 2.0 materials, and <a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_material_phong_brdf_fields\">VRML &gt;= 2.0 materials have the physical fields</a>. Because of this, also " . this_a_href_page('rayhunter 1.3.0', 'rayhunter') . " is released.</li>
</ul>

<p><a href=\"http://www.archlinux.org/\">Arch Linux</a> users may now install view3dscene from SVN easily by <a href=\"http://aur.archlinux.org/packages.php?ID=22782\">view3dscene Arch Linux package</a>. Thanks to Antonio Bonifati!
"),

    array('title' => 'view3dscene 3.2, engine 1.6 release: collisions in dynamic worlds',
          'year' => 2008,
          'month' => 12,
          'day' => 18,
          'pubDate' => /* date_to_timestamp.sh '2008-12-18' */ 1229601600,
          'guid' => '2008-12-18',
          'link' => 'http://vrmlengine.sourceforge.net/',
          'short_description' => '',
          'description' => "

<p>" . this_a_href_page('view3dscene 3.2', 'view3dscene') . " is released,
with a lot of improvements and optimizations for dynamic VRML/X3D worlds.
As usual, this is accompanied by " . this_a_href_page('underlying
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


    <p>I prepared a video showing a simple dynamic world written in X3D and played with view3dscene, see it on the right. The video is only a poor substitute for actually running and playing with this yourself, feeling the smoothness of all editing (the poor framerate of the video is only because of the capturing process...). So after downloading view3dscene, you're welcome to also download this <a href=\"https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/kambi_vrml_test_suite/x3d/dynamic_world.x3dv\">demo dynamic_world.x3dv</a> (or just grab it along with the rest of " . this_a_href_page('Kambi VRML test suite', 'kambi_vrml_test_suite') .
    ") and open it. It shows how you can edit the world by KambiScript, how changing transformations works fast, and how it all cooperates with collision detection &mdash; whatever scene you will build, your avatar will move honoring collision detection.

  <li><p>Changing <b><tt>Switch.whichChoice</tt> is greatly optimized</b>.
    Previously this was very costly operation, now it's instantaneous
    (almost no work is required).
    Many other optimizations were done for dynamic worlds, including
    <b>faster <tt>Transform</tt> traversing</b>, faster sensor's enabled switching,
    more reliable starting of time-dependent nodes and many more.

    <p>Thanks to <a href=\"http://www.de-panther.com/\">De-Panther</a> for
    pushing me to implement a lot of these features!

  <li>
<table align=\"right\">
  <tr><td>
    <a href=\"http://vrmlengine.sourceforge.net/images/progs_demo/original_size/shadows_dynamic_2.png\">
      <img align=\"right\" src=\"http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/shadows_dynamic_2.png\"
      alt=\"Dynamic shadows screenshot\"
      title=\"Dynamic shadows screenshot\"
    /></a>
  </td></tr>
</table>


    <p><b>Dynamic shadows support is greatly improved</b>, finally
    " . this_a_href_page('view3dscene', 'view3dscene') . " can render
    with shadows, honoring our <a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_shadows\">shadow's extensions</a>.
    We also have new <a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#section_ext_shadow_caster\">shadowCaster</a> extension.
    Oh, and shadows rendering with
    transparent objects is fixed. Just try the file
    <tt>x3d/kambi_extensions/shadows_dynamic.x3dv</tt> from
    " . this_a_href_page('Kambi VRML test suite', 'kambi_vrml_test_suite') . "
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
          'pubDate' => /* date_to_timestamp.sh '2008-11-09' */ 1226232000,
          'guid' => '2008-11-09',
          'link' => 'http://vrmlengine.sourceforge.net/',
          'short_description' => '',
          'description' =>
          "
<table align=\"right\">
  <tr><td>
    <a href=\"http://vrmlengine.sourceforge.net/images/progs_demo/original_size/chinchilla_normal.png\">
      <img align=\"right\" src=\"http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/chinchilla_normal.png\"
      alt=\"Normal OpenGL lighting\"
      title=\"Normal OpenGL lighting\"
    /></a>
  </td></tr>
  <tr><td>
    <a href=\"http://vrmlengine.sourceforge.net/images/progs_demo/original_size/chinchilla_simple_occlusion.png\">
      <img align=\"right\" src=\"http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/chinchilla_simple_occlusion.png\"
      alt=\"Rendering with simple ambient occlusion\"
      title=\"Rendering with simple ambient occlusion\"
    /></a>
  </td></tr>
  <tr><td>
    <a href=\"http://vrmlengine.sourceforge.net/images/progs_demo/original_size/chinchilla_diffuse_prt.png\">
      <img align=\"right\" src=\"http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/chinchilla_diffuse_prt.png\"
      alt=\"Precomputed Radiance Transfer\"
      title=\"Precomputed Radiance Transfer\"
    /></a>
  </td></tr>
</table>

<p>I implemented a demo of <a href=\"http://en.wikipedia.org/wiki/Precomputed_Radiance_Transfer\">Precomputed Radiance Transfer</a> using our engine.</p>

<p>In a few words, this is a technique to make very cool self-shadowing by soft shadows under dynamic lighting. (Actually it's possible to go much further, see the papers about PRT <a href=\"https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/kambi_vrml_game_engine/3dmodels.gl/examples/radiance_transfer/README\">linked from my README</a>). You can see the screenshots on the right: 1st shows normal OpenGL lighting (without PRT), 2nd shows the simple ambient occlusion per-vertex (this is, in some sense, a special case of PRT), and the 3rd screenshot shows PRT technique in all it's glory.</p>

<p>The full source code is available, naturally. Simple instructions:</p>

<pre>
$ svn checkout https://vrmlengine.svn.sourceforge.net/svnroot/vrmlengine/trunk/kambi_vrml_game_engine/
$ cd kambi_vrml_game_engine/3dmodels.gl/examples/radiance_transfer
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
          'pubDate' => /* date_to_timestamp.sh '2008-10-15' */ 1224072000,
          'guid' => '2008-10-15',
          'link' => 'http://vrmlengine.sourceforge.net/',
          'short_description' => '',
          'description' =>
this_a_href_page('view3dscene 3.1.0', 'view3dscene') . " release,
along with " . this_a_href_page('underlying
Kambi VRML game engine 1.5.0', 'kambi_vrml_game_engine') . " release.
Most notable improvements are:

<!--
http://vrmlengine.sourceforge.net/
http://127.0.0.1/~michalis/vrmlengine/
-->
<table align=\"right\">
  <tr><td>
    <a href=\"http://vrmlengine.sourceforge.net/images/progs_demo/original_size/kambi_script_ball_game.png\">
      <img align=\"right\" src=\"http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/kambi_script_ball_game.png\"
      alt=\"Simple game implemented in pure X3D with KambiScript\"
      title=\"Simple game implemented in pure X3D with KambiScript\"
    /></a>
  </td></tr>
  <tr><td>
    <a href=\"http://vrmlengine.sourceforge.net/images/progs_demo/original_size/kambi_script_particles.png\">
      <img align=\"right\" src=\"http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/kambi_script_particles.png\"
      alt=\"Particle engine programmed in pure X3D with KambiScript\"
      title=\"Particle engine programmed in pure X3D with KambiScript\"
    /></a>
  </td></tr>
  <tr><td>
    <a href=\"http://vrmlengine.sourceforge.net/images/progs_demo/original_size/kambi_script_edit_texture.png\">
      <img align=\"right\" src=\"http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/kambi_script_edit_texture.png\"
      alt=\"Texture editor (pure X3D with KambiScript)\"
      title=\"Texture editor (pure X3D with KambiScript)\"
    /></a>
  </td></tr>
</table>

<ul>
  <li><p><b>Scripting in " . this_a_href_page('KambiScript language',
    'kambi_script') . "</b>. KambiScript is a simple scripting language,
    invented specially for our engine. It's powerful
    enough for many tasks, you can process all VRML data types
    with it (including vectors, matrices, arrays, images).</p>

    <p>Screenshots on the right show example uses of KambiScript.
    Endless possibilities are available now for VRML authors, you can
    write complete interactive 3D games and run them with view3dscene
    (or any other VRML browser using our engine).
    " . this_a_href_page('Kambi VRML test suite 2.3.0', 'kambi_vrml_test_suite') .
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
          'pubDate' => /* date_to_timestamp.sh '2008-09-12' */ 1221220800,
          'guid' => '2008-09-12',
          'link' => 'http://vrmlengine.sourceforge.net/',
          'short_description' => '',
          'description' =>
"<p>I'm pleased to present the new, shiny
" . this_a_href_page('view3dscene 3.0', 'view3dscene') . " release,
with a lot of new <a href=\"http://www.web3d.org/\">VRML/X3D</a> features
implemented. Also " . this_a_href_page('underlying Kambi VRML game engine 1.4.0', 'kambi_vrml_game_engine') . "
is released and some other programs here get minor updates.</p>

<p>New features of the engine and view3dscene:</p>

<table align=\"right\">
  <tr><td>
    <a href=\"http://vrmlengine.sourceforge.net/images/progs_demo/original_size/deranged_house_final_0.png\">
      <img align=\"right\" src=\"http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/deranged_house_final_0.png\"
      alt=\"ProximitySensor in action\"
      title=\"ProximitySensor in action\"
    /></a>
  </td></tr>
  <tr><td>
    <a href=\"http://vrmlengine.sourceforge.net/images/progs_demo/original_size/ikea_bead_toy.png\">
      <img align=\"right\" src=\"http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/ikea_bead_toy.png\"
      alt=\"Animation (IkeaBeadToy model from www.web3d examples)\"
      title=\"Animation (IkeaBeadToy model from www.web3d examples)\"
    /></a>
  </td></tr>
</table>

<ul>
  <li><p><b>X3D support</b> (both XML and classic encoding).
    Our " . this_a_href_page('VRML implementation status', 'vrml_implementation_status') . "
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
    <a href=\"http://vrmlengine.sourceforge.net/vrml_implementation_status.php#shaders\">Routing
    events to GLSL shaders uniform variables works perfectly.</a>.
    Events to control behavior of <tt>Inline</tt> (and <tt>InlineLoadControl</tt>
    for VRML 97) work too.
    Prototypes and external prototypes also work 100% with events according
    to specification, so you can pass events to/from prototypes.
    New " . this_a_href_page('Kambi VRML test suite 2.2.0', 'kambi_vrml_test_suite') . "
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

  <li><p><a href=\"http://vrmlengine.sourceforge.net/images/progs_demo/original_size/view3dscene_thumbnailer_demo.png\">
    <img align=\"right\" src=\"http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/view3dscene_thumbnailer_demo.png\"
    alt=\"&quot;view3dscene&quot; as nautilus thumbnailer\" /></a>
    <a href=\"http://www.gnome.org/\">GNOME</a> users will be happy to
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

  <li><p>" . this_a_href_page('"The Castle" 0.8.2', 'castle') . " release also deserves
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
          'pubDate' => /* date_to_timestamp.sh '2008-08-15' */ 1218801600,
          'guid' => '2008-08-15',
          'link' => 'http://vrmlengine.sourceforge.net/',
          'short_description' => '',
          'description' =>
"<p><a href=\"http://vrmlengine.sourceforge.net/movies/laetitia_sprints.avi\"><img
  src=\"http://vrmlengine.sourceforge.net/images/progs_demo/medium_size/laetitia_sprints_demo.png\"
  alt=\"Laetitia Sprints by X3D TimeSensor + CoordinateInterpolator\"
  align=\"right\" /></a>

An extremely important feature of VRML / X3D is finally implemented:
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
          'pubDate' => /* date_to_timestamp.sh '2008-07-15' */ 1216123200,
          'guid' => '2008-07-15',
          'link' => 'http://vrmlengine.sourceforge.net/',
          'short_description' => '',
          'description' =>
'Various exciting news about development of our engine:

<ul>
  <li><p><a href="http://vrml.cip.ica.uni-stuttgart.de/dune/">White dune</a>,
    free software VRML 97 modeler,
    can export normal VRML animations
    (expressed in terms of VRML interpolators) to our ' .
    this_a_href_page('Kanim (Kambi animations) file format', 'kanim_format') .
    ' and it supports our ' .
    this_a_href_page('extension nodes and fields', 'kambi_vrml_extensions') .
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

      <li><p>

        ' . (!HTML_VALIDATION ?
        '<table align="right"><tr><td>
           <object width="200" height="167"><param name="movie" value="http://www.youtube.com/v/V-EJvVbi1DQ"> </param> <embed src="http://www.youtube.com/v/V-EJvVbi1DQ" type="application/x-shockwave-flash" width="200" height="167"> </embed> </object>
         </td></tr></table>' : '')
        . '

        Texture department:
        Textures with full alpha channel are now nicely rendered with blending
        (and textures will simple alpha channel are still detected and rendered
        faster by alpha_test). Moreover, <tt>MovieTexture</tt> node is now
        handled (movie can be read from image sequences, like <tt>image%d.png</tt>,
        and from normal movie formats thanks to <a href="http://ffmpeg.mplayerhq.hu/">ffmpeg</a>).
        As a demo, see the flames animation on the right.
        (You can also <a href="http://vrmlengine.sourceforge.net/movies/fireplace_demo.avi">download
        AVI version with perfect quality</a>.)</p>

      <li><p>Flames movie above was not only played in our ' .
        this_a_href_page('view3dscene', 'view3dscene') . ', it was also
        recorded directly by view3dscene. That\'s right: Screenshot options
        were much improved, it\'s now possible to capture animation
        as a movie file (with perfect quality, as opposed to using independent
        programs that capture OpenGL output).</p>

        <p><a href="' . CURRENT_URL . 'images/progs_demo/original_size/view3dscene_thumbnailer_demo.png">
        <img align="right" src="' . CURRENT_URL . 'images/progs_demo/medium_size/view3dscene_thumbnailer_demo.png"
        alt="&quot;view3dscene&quot; as nautilus thumbnailer" /></a>
        <a href="http://www.gnome.org/">GNOME</a> users will be happy to
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
          'pubDate' => /* date_to_timestamp.sh '2008-05-09' */ 1210334400,
          'guid' => '2008-05-09',
          'link' => 'http://vrmlengine.sourceforge.net/',
          'short_description' => '',
          'description' =>
"I present " . a_href_page('three demo movies', 'movies') . "
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
          'pubDate' => /* date_to_timestamp.sh '2008-02-25' */ 1203940800,
          'guid' => '2008-02-25',
          'link' => 'http://vrmlengine.sourceforge.net/',
          'short_description' => '',
          'description' =>
"Released " . a_href_page('engine version 1.3.1', 'kambi_vrml_game_engine') . ":
fixed Lazarus packages compilation, for developers that want to use our
engine with Lazarus."),

    array('title' => 'Engine 1.3.0 release, view3dscene 2.4.0, castle 0.8.1, many other releases',
          'year' => 2008,
          'month' => 2,
          'day' => 19,
          'pubDate' => /* date_to_timestamp.sh '2008-02-19' */ 1203422400,
          'guid' => '2008-02-19',
          'link' => 'http://vrmlengine.sourceforge.net/',
          'short_description' =>
"<p>Many long-awaited graphic features implemented in our engine.
Released " . a_href_page('engine version 1.3.0', 'kambi_vrml_game_engine') . ",
" . this_a_href_page('view3dscene 2.4.0', 'view3dscene') . " and
" . this_a_href_page('castle 0.8.1', 'castle') . ".
Below is only a shortcut of the most important changes
(see " . a_href_page('changes_log', 'changes_log') . " for a full list of changes) :</p>

<ul>
  <li><a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#ext_bump_mapping\">Bump
    mapping</a>. Various bump mapping methods are implemented,
    the most advanced being steep parallax mapping with self-shadowing.</li>

  <li>Shaders support, including <a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#ext_shaders\">specifying GLSL
    shaders in VRML</a>. Programmers may easily initialize
    GLSL and ARB assembly shaders.</li>

  <li>Anti-aliasing available in both "
    . this_a_href_page('view3dscene', 'view3dscene') . "
    and " . this_a_href_page('castle', 'castle') . ".</li>

  <li>Collada model format basic support (1.3.x and 1.4.x)
    added to the engine, you can also convert Collada files to VRML 2.0.</li>

  <li><i>Examine</i> mode allows to rotate and move the scene by mouse
    dragging.</li>

  <li><tt>--screenshot</tt> command-line option for
    " . this_a_href_page('view3dscene', 'view3dscene') . ",
    to take screenshots of the scene in batch mode.</li>

  <li>" . this_a_href_page('Our Blender VRML 97 exporter script', 'blender_stuff') . "
    improved: <i>set solid / set smooth / autosmooth / autosmooth degrees</i>
    settings from Blender are correctly exported to VRML.</li>
</ul>

<p>Other releases:
" . this_a_href_page('Kambi VRML test suite 2.1.0', 'kambi_vrml_test_suite') . "
has many new tests/demos for new features (bump mapping, GLSL,
Collada format). Also released most other programs,
to bring them up-to-date with current engine state.</p>
",
          'description' =>

"<p>Released " . a_href_page('engine version 1.3.0', 'kambi_vrml_game_engine') . ",
" . this_a_href_page('view3dscene 2.4.0', 'view3dscene') . " and
" . this_a_href_page('castle 0.8.1', 'castle') . ".
Many long-awaited graphic features implemented:</p>

<ul>
  <li><p><b>Bump mapping</b>: <a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#ext_bump_mapping\">VRML
    renderer allows bump mapping</a>. Various bump mapping methods
    are implemented (best method is auto-detected and used at runtime):
    dot by multitexturing (not normalized and normalized by cube map),
    dot by GLSL (optionally with parallax mapping, optionally with
    steep parallax mapping and self-shadowing).</p>

    <p>" . this_a_href_page('view3dscene', 'view3dscene') . " allows to easily
    turn on bump mapping, assuming model specifies normal maps.<br/>
    " . this_a_href_page('castle', 'castle') . " uses bump mapping, for now only
    on the \"fountain\" level.</p>

    <p><i>For programmers</i>: see also <tt>kambi_vrml_game_engine/3dmodels.gl/examples/bump_mapping</tt> demo in engine sources,
    it demonstrates emboss, dot and all other bump mapping
    methods built in VRML engine. Also my notes about emboss and dot
    (by multitexturing) bump mapping methods may be interesting:
    see <a href=\"http://vrmlengine.svn.sourceforge.net/viewvc/*checkout*/vrmlengine/trunk/kambi_vrml_game_engine/3dmodels.gl/examples/bump_mapping/README\">bump_mapping/README</a>.</p>
  </li>

  <li><p><b>GLSL shaders support:</b> engine supports easily using
    ARB vertex / fragment programs (assembly shaders) and&nbsp;GLSL.</p>

    <p>You can also <a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#ext_shaders\">directly
    specify GLSL shaders inside VRML file</a>, which is a great feature
    for VRML authors. Syntax of shaders in VRML follows X3D specification.</p>

    <p>" . this_a_href_page('view3dscene', 'view3dscene') . " allows to control
    GLSL shaders and even simply assign GLSL shaders
    (check out <i>Edit -&gt; Simply assign GLSL shader to all objects</i>
    menu item), so you can test your shaders with any 3D model.</p>

    <p><i>For programmers</i>: you may find useful my notes about shading languages in
    <a href=\"http://vrmlengine.svn.sourceforge.net/viewvc/*checkout*/vrmlengine/trunk/kambi_vrml_game_engine/opengl/examples/shading_langs/README\">shading_langs_demo/README</a>.</p>

  <li><p><b>Anti-aliasing</b> available (if multisampling is supported by
    graphic card). " . this_a_href_page('view3dscene', 'view3dscene') . "
    has comfortable menu <i>File -&gt; Startup Preferences -&gt; Anti aliasing</i>
    and also a command-line option <tt>--anti-alias</tt> to control this,
    " . this_a_href_page('castle', 'castle') . " has comfortable menu item
    in <i>Video options</i>.</p>

  <li><p><b>Collada model format</b> basic support (1.3.x and 1.4.x)
    added to the engine (" . this_a_href_page('view3dscene', 'view3dscene') . "
    and everything else open Collada files, just like any other model format;
    you can also convert Collada files to VRML 2.0).</p>
  </li>

  <li><p>Wavefront OBJ format handling improved (we handle normal vectors,
    materials, textures).</p>
  </li>

  <li><p><tt>Examine</tt> mode allows to rotate and move the scene by mouse
    dragging. This is more intuitive and sometimes comfortable.
    This feature is mostly noticeable in
    " . this_a_href_page('view3dscene', 'view3dscene') . ", although
    some example programs in engine demos also use such examine mode
    and also benefit from this.</p>
  </li>
</ul>

<p>Besides improvements above, some improvements specific to
" . this_a_href_page('view3dscene', 'view3dscene') . ":</p>

<ul>
  <li><tt>--screenshot</tt> command-line option, requested a few times,
    to take screenshots of the scene in batch mode.</li>
  <li>Various <i>fill modes</i>. Previously only <i>normal</i> and
    <i>wireframe</i> were available, now we have a couple more,
    like solid wireframe and silhouette. Keys changed (\"w\" key no longer works,
    \"f\" key changes fill mode instead of fog state).</li>
</ul>

<p>Also " . this_a_href_page('our Blender VRML 97 exporter script', 'blender_stuff') . "
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
    <tt>kambi_vrml_game_engine/3dmodels.gl/examples/plane_mirror_and_shadow.pasprogram</tt>
    to test plane-projected shadows and plane mirrors. Plane-projected shadows
    is only for simple demo (we have implemented shadow volumes, thousand times better
    algorithm, after all), but plane mirrors will be implemented
    in the future in the VRML engine (using \"mirror\" <tt>Material</tt>
    field automatically).</li>
</ul>

<p>Other releases:
" . this_a_href_page('Kambi VRML test suite 2.1.0', 'kambi_vrml_test_suite') . "
has many new tests/demos for new features (bump mapping, GLSL,
Collada format). Also released:
" . this_a_href_page('rayhunter 1.2.2', 'rayhunter') . ",
" . this_a_href_page('lets_take_a_walk 1.2.1', 'lets_take_a_walk') . ",
" . this_a_href_page('malfunction 1.2.4', 'malfunction') . ",
" . this_a_href_page('kambi_lines 1.1.4', 'kambi_lines') . ",
" . this_a_href_page('glplotter 1.2.1', 'glplotter_and_gen_function') . ",
" . this_a_href_page('glViewImage 1.2.2', 'glviewimage') . ",
" . this_a_href_page('bezier_curves 1.1.6', 'bezier_curves') . ",
" . this_a_href_page('glcaps 1.1.2', 'glcaps') . ",
mainly to bring them up-to-date with current engine state.</p>
"),

/* --------------------------------------------------------------------------- */

    array('title' => 'castle 0.8.0, view3dscene 2.3.0 released',
          'year' => 2007,
          'month' => 11,
          'day' => 17,
          'pubDate' => /* date_to_timestamp.sh '2007-11-17' */ 1195300800,
          'guid' => '2007-11-17',
          'link' => 'http://vrmlengine.sourceforge.net/',
          'short_description' =>
"<p>A lot of updates today. Here's a shortcut of only the most important changes
(see " . a_href_page('changes_log', 'changes_log') . " for a full list of changes) :
<ul>
  <li>" . a_href_page('"The Castle" 0.8.0', 'castle') . " released:
    new demo level <i>the fountain</i> (VRML 2.0, dynamic shadows),
    many shadows improvements (z-fail, proper detection z-pass/z-fail, shadow
    culling etc.), conserve memory feature (all Radeon issues should be fixed
    now).</li>
  <li>" . a_href_page('view3dscene 2.3.0', 'view3dscene') . " released:
    prototypes (both <tt>PROTO</tt> and <tt>EXTERNPROTO</tt>),
    VRML 2.0 lights are correctly handled,
    handling of colors for <tt>IndexedFaceSet</tt> and <tt>IndexedLineSet</tt>,
    <a href=\"http://vrmlengine.sourceforge.net/kambi_vrml_extensions.php#ext_text3d\">Text3D extension</a>.</li>
  <li>" . a_href_page('Kambi VRML game engine 1.2.0', 'kambi_vrml_game_engine') . "
    released: most things mentioned above were actually implemented in the base
    engine units, also: x86-64 port.</li>
  <li>" . a_href_page('Kambi VRML test suite 2.0.0', 'kambi_vrml_test_suite') . "
    released: many new tests for new features.</li>
  <li>" . a_href_page('Blender VRML stuff page added, with improved VRML 2.0
    exporter and kanim exporter', 'blender_stuff') . ".</li>
  <li>Updated version of " . a_href_page('VRML engine documentation',
    'vrml_engine_doc') . " is available, with a
    <a href=\"http://vrmlengine.sourceforge.net/vrml_engine_doc/output/xsl/html/chapter.shadows.html\">chapter
    about shadows implementation.</a></li>
</ul>",
          'description' =>

"<p>" . this_a_href_page('"The Castle" 0.8.0', 'castle') . " released:

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
        <tt>kambi_vrml_game_engine/3dmodels.gl/examples/shadow_volume_test/</tt>
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

<p>" . this_a_href_page('view3dscene 2.3.0', 'view3dscene') . " released:

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

<p>" . this_a_href_page('Kambi VRML game engine 1.2.0', 'kambi_vrml_game_engine') . "
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

<p>" . this_a_href_page('Kambi VRML test suite 2.0.0', 'kambi_vrml_test_suite') . "
released: many new tests to test new features (protos, external protos,
colors, light scope, running path to test NavigationInfo.speed, 3d text),
some important VRML 1.0 tests ported to VRML 2.0 too (castle,
relative_names, texture_test, house behind the glass).</p>

<p>" . this_a_href_page('Blender VRML stuff page added, with improved VRML 2.0
exporter and kanim exporter', 'blender_stuff') . ".</p>

<p>Updated version of " . this_a_href_page('VRML engine documentation',
'vrml_engine_doc') . " is available, with a chapter about shadows
implementation.</p>
"),

/* --------------------------------------------------------------------------- */

    array('title' => 'glplotter 1.2.0 and view3dscene 2.2.1 released',
          'year' => 2007,
          'month' => 9,
          'day' => 6,
          'pubDate' => /* date_to_timestamp.sh '2007-09-06' */ 1189080000,
          'guid' => '2007-09-06',
          'link' => 'http://vrmlengine.sourceforge.net/',
          'short_description' => '',
          'description' =>

"<ul>
  <li>" . this_a_href_page('glplotter 1.2.0 and gen_function 1.0.2',
    'glplotter_and_gen_function') . " released: glplotter GUI greatly improved:
    Open/Add menu items to open graphs from files
    and to generate graphs from function expressions.
    This means that now you don't have to specify graphs at command-line,
    and now you don't have to write pipes with gen_function.
    Also documentation and some options translated finally to English.</li>
  <li>" . this_a_href_page('view3dscene 2.2.1', 'view3dscene') . " released:
    bug fix release. Fixed crash when removing geometry node from
    VRML 2.0 hierarchy. Fixed jagged animation when world time was
    really large (usually occurs when \"on display\" time pass was
    really large for some time). Fixed messing the gravity
    \"up\" vector when reopening the scene.</li>
  <li>" . this_a_href_page('Kambi VRML game engine 1.1.1',
    'kambi_vrml_game_engine') . " released: changes needed by
    view3dscene and glplotter above.</li>
  <li><a href=\"http://vrmlengine.sourceforge.net/changes_log_feed.php\">RSS
    feed</a> listing all changes is available now.
    <small>SouceForge already made RSS feeds for our project,
    but they didn't allow me HTML code there, and HTML links
    are simply useful for my changes_log messages.</small></li>
</ul>"),

/* --------------------------------------------------------------------------- */

    array('title' => 'view3dscene 2.2.0 and related releases',
          'year' => 2007,
          'month' => 8,
          'day' => 25,
          'pubDate' => /* date_to_timestamp.sh '2007-08-25' */ 1188043200,
          'guid' => '2007-08-25',
          'link' => 'http://vrmlengine.sourceforge.net/',
          'short_description' => '',
          'description' =>

"<ul>
  <li>" . this_a_href_page('view3dscene 2.2.0', 'view3dscene') . " release:
    view3dscene can display animations now (for now in " .
     this_a_href_page(
    "Kanim (Kambi VRML engine animations) format", 'kanim_format') . " and
    MD3).</li>
  <li>" . this_a_href_page('Kambi VRML test suite 1.1.0',
    'kambi_vrml_test_suite') . " release: many kanim demos added.</li>
  <li>" . this_a_href_page('Kambi VRML game engine 1.1.0',
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
          'pubDate' => /* date_to_timestamp.sh '2007-07-25' */ 1185364800,
          'guid' => '2007-07-25',
          'link' => 'http://vrmlengine.sourceforge.net/',
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
          'pubDate' => /* date_to_timestamp.sh '2007-07-23' */ 1185192000,
          'guid' => '2007-07-23',
          'link' => 'http://vrmlengine.sourceforge.net/',
          'short_description' => '',
          'description' =>

"<p>Download links for most VRML stuff on this page direct to SourceForge
file release system now. This is another step in moving to
<a href=\"http://sourceforge.net/projects/vrmlengine\">vrmlengine
on SourceForge</a>.

<p>Also, some things now get version numbers:
" . this_a_href_page('Kambi VRML game engine', 'kambi_vrml_game_engine') . " (1.0.0),
" . this_a_href_page("Kambi VRML test suite", "kambi_vrml_test_suite") . " (1.0.0).
</p>")

  );

/* --------------------------------------------------------------------------- */

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

function change_log_to_html($change_log_item, $full_description = true)
{
  global $month_names;

  $description = ($full_description || $change_log_item['short_description'] == '' ?
    $change_log_item['description'] :
    $change_log_item['short_description']);

  return '<p><b>' .
    $change_log_item['title'] . '</b> (' .
    $month_names[$change_log_item['month']] . ' ' .
    $change_log_item['day'] . ', ' .
    $change_log_item['year'] . ') :</p>' .
    $description;
}

function last_change_log_to_html($full_description = true)
{
  global $changes_log;

  return change_log_to_html($changes_log[0], $full_description);
}

?>
