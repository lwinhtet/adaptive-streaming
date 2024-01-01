import 'video.js/dist/video-js.css';
import videojs from 'video.js';

document.addEventListener('DOMContentLoaded', function () {
  /* setting up a hook for modifying HTTP requests made by the Video.js HTTP Streaming (VHS) component. 
  Specifically, it is using the beforeRequest hook to modify the options of an HTTP request before it is sent.
  As for when this code will run, it will execute whenever an HTTP request is about to be made by the VHS component. */
  videojs.Vhs.xhr.beforeRequest = function (options) {
    let newUri = options.uri.includes('.ts')
      ? options.uri + '?q=testDePrueba'
      : options.uri;

    return {
      ...options,
      uri: newUri,
    };
  };

  var videoElement = document.getElementById('my-video');

  let player = videojs(videoElement, {}, () => {
    // sets up an event listener for the 'loadedmetadata' event,
    // which triggers once the metadata for the video is loaded.
    player.one('loadedmetadata', () => {
      /* If including videojs-contrib-quality-levels is not an option, you can use the 
      representations api. To get all of the available representations, call the 
      representations() method on player.tech().vhs. This will return a list of plain objects, 
      each with width, height, bandwidth, and id properties, and an enabled() method. 
      To see whether the representation is enabled or disabled, call its enabled() method with 
      no arguments. To set whether it is enabled/disabled, call its enabled() method and pass in 
      a boolean value. Calling <representation>.enabled(true) will allow the adaptive bitrate algorithm 
      to select the representation while calling <representation>.enabled(false) will disallow any selection 
      of that representation.
      */

      /* { IWillNotUseThisInPlugins: true } is an safety object
      Read Here: "https://github.com/videojs/video.js/issues/2617" */
      let qualities = player
        .tech({ IWillNotUseThisInPlugins: true })
        .vhs.representations();

      // create buttons for each video quality and add them to the player's control bar.
      createButtonQualities({
        class: 'item',
        qualities: qualities,
        father: player.controlBar.el_,
      });

      player.play();

      // ---------------------------------------------- //

      // Creates a button for automatic quality selection.
      function createButtonAutoQuality(params) {
        let button = document.createElement('div');

        button.id = 'auto';
        button.innerText = `Auto`;

        button.classList.add('selected');

        if (params && params.class) button.classList.add(params.class);

        button.addEventListener('click', () => {
          removeSelected(params);
          button.classList.add('selected');
          qualities.map((q) => q.enabled(true));
        });

        return button;
      }

      // Creates buttons for each video quality and appends them to the player's control bar.
      function createButtonQualities(params) {
        let contentMenu = document.createElement('div');
        let menu = document.createElement('div');
        let icon = document.createElement('div');

        let fullscreen = params.father.querySelector('.vjs-fullscreen-control');
        contentMenu.appendChild(icon);
        contentMenu.appendChild(menu);
        fullscreen.before(contentMenu);

        menu.classList.add('menu');
        icon.classList.add('icon', 'vjs-icon-cog');
        contentMenu.classList.add('contentMenu');

        let buttonAuto = createButtonAutoQuality(params);

        menu.appendChild(buttonAuto);

        qualities.sort((a, b) => {
          return a.height > b.height ? 1 : 0;
        });

        qualities.map((q) => {
          let button = document.createElement('div');

          if (params && params.class) button.classList.add(params.class);

          button.id = `${q.height}`;
          button.innerText = q.height + 'p';

          button.addEventListener('click', () => {
            resetQuality(params);
            button.classList.add('selected');
            q.enabled(true);
          });

          menu.appendChild(button);
        });

        setInterval(() => {
          let auto = document.querySelector('#auto');
          // get height value from resolution
          var current = player
            .tech({ IWillNotUseThisInPlugins: true })
            .vhs.selectPlaylist().attributes.RESOLUTION.height;

          // console.log(current);

          document.querySelector('#auto').innerHTML = auto.classList.contains(
            'selected'
          )
            ? `Auto <span class='current'>${current}p</span>`
            : 'Auto';
        }, 1000);
      }

      // Removes the 'selected' class from quality buttons.
      function removeSelected(params) {
        document.querySelector('#auto').classList.remove('selected');
        [...document.querySelectorAll(`.${params.class}`)].map((quality) => {
          quality.classList.remove('selected');
        });
      }

      // Disables all video qualities.
      function resetQuality(params) {
        removeSelected(params);

        for (let quality of params.qualities) {
          quality.enabled(false);
        }
      }
    });
  });
});
