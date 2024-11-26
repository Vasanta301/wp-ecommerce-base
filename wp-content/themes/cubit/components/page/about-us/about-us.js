document.addEventListener("alpine:init", () => {
  Alpine.data("AboutVideo", () => ({
    player: null,
    init() {
      const videoType = this.$refs.video.dataset.type;
      const videoUrl = this.$refs.video.dataset.url;

      console.log("Video URL:", videoUrl); // Log video URL for debugging
      console.log("Video Type:", videoType); // Log video type for debugging

      if (videoType === "youtube" && videoUrl) {
        const videoId = this.extractAboutVideoId(videoUrl);
        if (videoId) {
          if (window.YT) {
            this.createPlayer(videoId);
          } else {
            console.error("YouTube IFrame API not loaded.");
          }
        } else {
          console.error("Invalid YouTube URL");
        }
      } else {
        console.error("Invalid video type or missing URL");
      }
    },
    createPlayer(videoId) {
      window.YT.ready(() => {
        this.player = new YT.Player(this.$refs.video, {
          width: "100%",
          height: "100%",
          videoId: videoId,
        });
      });
    },
    extractAboutVideoId(url) {
      const regExp =
        /(?:youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=|v\/|.+\?v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
      const match = url.match(regExp);

      if (match && match[1]) {
        console.log("Extracted video ID:", match[1]);
        return match[1];
      } else {
        console.error("Invalid YouTube URL");
        return null;
      }
    },
  }));
});
