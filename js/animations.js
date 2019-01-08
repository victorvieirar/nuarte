var waves = new SineWaves({
    el: document.getElementById('waves'),
    
    speed: 4,
    
    width: function() {
      return $(window).width();
    },
    
    height: function() {
      return 200;
    },
    
    ease: 'SineInOut',
    
    wavesWidth: '100%',
    
    waves: [
      {
        timeModifier: 4,
        lineWidth: 1,
        amplitude: -25,
        wavelength: 25
      },
      {
        timeModifier: 2,
        lineWidth: 2,
        amplitude: 50,
        wavelength: -80
      },
      {
        timeModifier: 1,
        lineWidth: 1,
        amplitude: -80,
        wavelength: 100
      }
    ],
   
    // Called on window resize
    resizeEvent: function() {
      var gradient = this.ctx.createLinearGradient(0, 0, this.width, 0);
      gradient.addColorStop(0,"#3DEFE1");
      gradient.addColorStop(1,"#28B2F0");
      
      var index = -1;
      var length = this.waves.length;
        while(++index < length){
        this.waves[index].strokeStyle = gradient;
      }
      
      // Clean Up
      index = void 0;
      length = void 0;
      gradient = void 0;
    }
  });

  function slideDown(element) {
    $(element).css('display', 'flex').animate({
      top: '0',
    }, 400);
  }

  function slideUp(element) {
    $(element).css('display', 'flex').animate({
      top: '-100vh',
    }, 400);
  }