<?php
  require_once 'castle_engine_functions.php';
  require_once 'x3d_implementation_common.php';
  vrmlx3d_header("VRML / X3D time origin considered uncomfortable");
?>

<?php echo pretty_heading($page_title); ?>

<p><b>This is mostly a rant about why I (<?php echo michalis_mailto('Michalis'); ?>)
think that VRML/X3D idea of time origin ("January 1, 1970") is a bad idea.
Don't worry, Castle Game Engine complies with VRML/X3D standard in this regard
&mdash; I'm just quite unhappy about this.</b> You can change the behavior
to more sane (in my opinion) by using <?php echo a_href_page_hashlink(
  'our extension <code>KambiNavigationInfo.timeOriginAtLoad</code>',
  'x3d_extensions',
  'section_ext_time_origin_at_load'); ?>, also precalculated animations from
<?php echo a_href_page('Kanim', 'kanim_format'); ?> and MD3 formats will always start playing
from their begin-time, usually 0.0.</p>

<p>VRML/X3D have an idea that time stored in <code>SFTime</code>
corresponds to a real-world time. More precisely, it's the number
of seconds since <i>00:00:00 GMT January 1, 1970</i>.
This affects time-dependent
nodes behavior, like <code>TimeSensor</code> and <code>MovieTexture</code>,
and timestamps generated by events.</p>

<p>As far as I'm concerned, this is a broken idea,
but <?php echo a_href_page('view3dscene',
'view3dscene'); ?> honors it anyway. Any general-purpose VRML browser must
honor it in some way, not necessarily by using real-world
time, but at least by setting initial time to some very large value.
Reason: otherwise many animations in VRML files start playing
immediately after file is loaded, and VRML authors don't expect
this. Default field values are designed such that
a default time-dependent node (with default <code>loop = FALSE</code>)
should play one cycle from time 0 to the end of it's cycle.
If a browser starts with real-world time value, this is
a very large time value,
larger than usual cycle interval, so node will not play at all.

<p>So VRML authors learned to expect that actually "default values
for time-dependent nodes mean that node doesn't play when file
is loaded".

<p>Why this is broken idea in my opinion?

<ol>
  <li><p>The main problem is that this prevents user from pausing
    the animation if you continuously supply time values as
    real-world time. There's no way to just "pause" the animation.
    It may not be rendered for some time, but real-world time
    is always ticking. Well that's why it's called "real" world time after all.

    <p>That's why view3dscene doesn't really supply real-world time.
    Although initial VRML time is taken from real-world time,
    it's not guaranteed to be synchronized with real-world time.
    As soon as you pause the animation, or open some modal window,
    time pass is paused, and VRML world time is no longer synchronized
    with real-world time. This way you can "pause" the animation,
    which is rather useful feature.

  <li><p>Another trouble is that VRML authors cannot easily synchronize
    starting of the animation with loading of the file.
    <code>startTime = 0</code> is useless, as "0" means "January 1, 1970".
    For constantly looping animations (<code>loop = TRUE</code>,
    rest of the fields as default) this is also a problem,
    as you have no idea in what stage of the animation you
    are when loading the file. Making some "welcome" animation
    requires you to use tricks to route some
    sensor like <code>ProximitySensor</code> (positioned to
    include default viewpoint) to time-dependent node.
    Which isn't difficult, but I feel it's needlessly complicated.

    <p>That's why view3dscene allows VRML author to
    <?php echo a_href_page_hashlink(
    'change VRML time origin by <code>KambiNavigationInfo.timeOriginAtLoad</code>',
    'x3d_extensions',
    'section_ext_time_origin_at_load'); ?>.
    This allows you to use <code>startTime = 0</code> predictably.
    Also, user has menu item "<i>Animation -&gt; Rewind to the
    Beginning</i>", for testing.

    <p>When using my engine to develop your own games, you can
    simply start VRML time from 0.0 (by <code>TCastleSceneCore.ResetTime(0.0))</code>,
    or to any other value you want. For example, setting it to some
    large but determined value, like exactly a million, allows
    you to work correctly with standard animations and at the same
    time you're able to express <code>startTime</code> relative to loading time.

  <li><p>Large time values are not nice to show to the user.
    It's strange to average user to see time value like
    <code>1220626229.13</code> immediately after opening the file.
    And manual input
    of such time values is difficult. This is a pity,
    as sometimes I really have to ask or show VRML time
    for user: for example when recording the VRML animation
    (view3dscene can record animation to the movie,
    or as a precalculated animation), and for things
    like <code>Logger</code> node output timestamps.

    <p>To remedy this at least a little, view3dscene displays time
    as <i>World time: load time + %f = %f</i> for standard VRML files
    (that do not use <code>timeOriginAtLoad = TRUE</code>).
    This way user sees also the simpler time (since load).

  <li><p>A minor problem is also that user doesn't expect
    different behavior of VRML/X3D world depending on the real-world
    time at which it is loaded. True, it opens some interesting
    possibilities (VRML/X3D world may adjust to real-world
    day/night state for example), but also some nightmarish
    scenarios ("<i>VRML/X3D world crashes with segfault but only
    when opened ~5 minutues after the midnight</i>" &mdash; now imagine you have to
    debug this :) ).
</ol>

<p>More sane definition of "time origin" would seem to be
"for single-user games, time origin 0.0 is equivalent to the time when
browser finished initialization
and presented VRML world to the user, starting VRML sensors listening
and events processing". (For multi-player games over the network,
real-world time or some other server time may be more appropriate indeed.)
Actually this is exactly done
when <?php echo a_href_page_hashlink(
  'our extension <code>KambiNavigationInfo.timeOriginAtLoad = TRUE</code>',
  'x3d_extensions',
  'section_ext_time_origin_at_load'); ?>.
 This also means that time-dependent node with all fields set as default
plays exactly once when the model is loaded &mdash; which is actually quite
sensible default behavior for me. (You can always set for example
<code>startTime = -1</code> and <code>stopTime = -0.5</code> to prevent node from playing.)
<!-- Alternative solution would be
to push default <code>startTime</code> and such fields into the past. -->

<?php
  vrmlx3d_footer();
?>

