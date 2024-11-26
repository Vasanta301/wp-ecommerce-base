document.addEventListener('alpine:init', () => {
  Alpine.data('Preloader', () => ({
    init() {
      const isFirstLoad = !window.sessionStorage.getItem('is-first-load')
      window.sessionStorage.setItem('is-first-load', false)

      document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', e => {
          // If user is trying to open link in new tab
          if (
            e.ctrlKey ||
            e.shiftKey ||
            e.metaKey || // apple
            (e.button && e.button == 1) // middle click, >IE9 + everyone else
          ) {
            return
          }
          if (link.getAttribute('target') != '_blank') {
            if ((link.href.startsWith('http') || link.href.startsWith('//'))) {
              const linkPageUrl = new URL(link.href)
              const linkPage = linkPageUrl.origin + linkPageUrl.pathname
              const currentPage = window.location.origin + window.location.pathname
              if (linkPage != currentPage) {
                e.preventDefault()

                gsap
                  .timeline()
                  .fromTo(
                    this.$refs.panel,
                    { xPercent: 100 },
                    {
                      ease: 'power1.in',
                      xPercent: 0,
                      duration: 0.75,
                    }
                  )
                  .fromTo(
                    this.$refs.c,
                    { opacity: 0 },
                    {
                      ease: 'power1.in',
                      duration: 0.5,
                      opacity: 1,
                      onComplete: () => {
                        window.location = link.href
                      },
                    }
                  )
              }
            }
          }
        })
      })

      const animateOut = () => {
        if (isFirstLoad) {
          const tl = gsap.timeline().delay(0.1)
          if (window.innerWidth > 640) {
            if (this.$refs.textL && this.$refs.textR) {
              tl.add(
                gsap
                  .timeline()
                  .to(
                    this.$refs.textL,
                    {
                      ease: 'power3.out',
                      duration: 1,
                      x: 0,
                    },
                    0
                  )
                  .to(
                    this.$refs.textR,
                    {
                      ease: 'power3.out',
                      duration: 1,
                      x: 24,
                    },
                    0
                  )
              )
            }
          }
          tl.to(
            [this.$refs.textL, this.$refs.textR, this.$refs.c].filter(
              el => !!el
            ),
            {
              ease: 'power1.out',
              duration: 0.75,
              opacity: 0,
            }
          ).to(this.$refs.panel, {
            ease: 'power2.inOut',
            xPercent: -100,
            duration: 1.75,
          })
        } else {
          gsap
            .timeline()
            .to(this.$refs.c, {
              ease: 'power1.out',
              duration: 0.5,
              opacity: 0,
            })
            .to(this.$refs.panel, {
              ease: 'power1.out',
              xPercent: -100,
              duration: 0.75,
            })
        }
      }

      window.addEventListener('load', animateOut)
      window.addEventListener('pageshow', animateOut)
    },
  }))
})
