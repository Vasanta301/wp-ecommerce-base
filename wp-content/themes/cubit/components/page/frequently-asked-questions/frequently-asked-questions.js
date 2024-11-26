document.addEventListener("alpine:init", () => {
    Alpine.data("ModalVideo", () => ({
      open: false,
      player: null,
      init() {
        const videoType = this.$refs.video?.dataset.type;
        const videoUrl = this.$refs.video?.dataset.url;
  
        console.log("Video URL:", videoUrl); 
  
        if (videoType === "youtube" && videoUrl) {
          const videoId = extractVideoId(videoUrl);
          if (videoId) {
            window.YT.ready(() => {
              this.player = new YT.Player(this.$refs.video, {
                width: "100%",
                height: "100%",
                videoId: videoId,
                events: {
                  onReady: () => {
                    // Optionally, you can start playing the video automatically
                    // this.player.playVideo();
                  }
                }
              });
            });
          } else {
            console.error("Invalid YouTube URL");
          }
        } else {
          console.error("Invalid video type or missing URL");
        }
      },
    }));
  });
  
  function extractVideoId(url) {
    const regExp =
      /^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=|v\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
    const match = url.match(regExp);
  
    if (match && match[1]) {
      console.log("Extracted video ID:", match[1]);
      return match[1];
    } else {
      console.error("Invalid YouTube URL");
      return null;
    }
  }
  