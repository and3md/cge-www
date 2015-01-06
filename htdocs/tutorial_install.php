<?php
require_once 'castle_engine_functions.php';
tutorial_header('Download and install the engine, try the demos');
?>

<p>If you haven't done it yet, <?php echo a_href_page('download the
engine source code with examples', 'engine'); ?>.</p>

<p>The example program that shows most of the features presented in
this tutorial is inside <code>examples/fps_game/fps_game.lpr</code> .
I suggest looking at it's source
code for a complete implementation that uses most the code snippets
shown in this tutorial.</p>

<ul>
  <li><p><b>If you want to develop using <a href="http://www.lazarus.freepascal.org/">Lazarus</a>:</b>
    Run Lazarus,
    and open packages in the <code>castle_game_engine/packages/</code>
    subdirectory. Open and compile all three packages (<code>castle_base.lpk</code>,
    <code>castle_components.lpk</code>, <code>castle_window.lpk</code>), to test that things compile
    OK. Then install the package <code>castle_components.lpk</code> (it will
    also install <code>castle_base.lpk</code> by dependency).
    (Do not install
    <code>castle_window.lpk</code> &mdash; you don't want to have this installed in
    Lazarus.)</p>

    <p>Once packages are successfully installed, Lazarus restarts, and you
    should see the <i>"Castle"</i> tab with our components.
    <!--
     at the top (TODO: screenshot). Sorry,
    we don't have icons for our components yet, so it looks a little
    boring. Mouse over the icons to see component names.--></p>
  </li>

  <li><p><b>Alternatively, our engine can be used without Lazarus and LCL
    (Lazarus Component Library). Only <a href="http://www.freepascal.org/">FPC
    (Free Pascal Compiler)</a> is required</b>. Many of the example programs
    use our own <code>CastleWindow</code> unit to communicate with window manager,
    and they do not depend on Lazarus LCL.
    You can use command-line <code>xxx_compile.sh</code> scripts (or just call
    <code>make examples</code>) to compile them using FPC.

    <p>You will not be able to compile components and examples using LCL
    of course (things inside <code>src/components/</code> and <code>examples/lazarus/</code>).
  </li>
</ul>

<p>Let's quickly open and run some demos, to make sure that everything
works. I suggest running at least
<code>examples/lazarus/model_3d_viewer/</code> (demo using Lazarus LCL) and
<code>examples/fps_game/</code> (demo using our CastleWindow unit).</p>

<p>Make sure you have installed the necessary libraries first, or some of
the demos will not work. The required libraries are mentioned near the <?php
echo a_href_page_hashlink('engine download', 'engine', 'section_download_src'); ?>.
<!--
<a
href="http://castle-engine.sourceforge.net/apidoc/html/introduction.html#SectionLibraries">Requirements
-&gt; Libraries</a> section of our reference introduction.
-->
Under Windows, you will usually want to grab the necessary DLLs from: <a href="http://castle-engine.sourceforge.net/miscella/win32_dlls.zip">here (32-bit libraries zipped)</a>  or <a href="http://svn.code.sf.net/p/castle-engine/code/trunk/external_libraries/i386-win32/">here (directory with 32-bit libraries)</a> or <a href="http://svn.code.sf.net/p/castle-engine/code/trunk/external_libraries/x86_64-win64/">here (directory with 64-bit libraries)</a>. Then place dll files
somewhere on your $PATH, or just place them in every directory
with .exe files that you compile with our engine.</p>

<p>If you are interested in developing for Android or iOS, you will want
to later read also
<ul>
  <li><a href="http://sourceforge.net/p/castle-engine/wiki/Android%20development/">Developing for Android</a>
  <li><a href="http://sourceforge.net/p/castle-engine/wiki/iOS%20Development/">Developing for iOS (iPhone, iPad)</a>
</ul>

<p>Now we'll start creating our own game from scratch.</p>

<?php
tutorial_footer();
?>
