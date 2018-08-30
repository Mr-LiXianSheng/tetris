<template>
  <div id="canvas-bg">
    <canvas ref="canvas-bg"></canvas>
  </div>
</template>

<script>
export default {
  name: 'CanvasBG',
  data () {
    return {
      // canvas dom element
      canvas: {},
      // canvas draw context
      ctx: {},
      // canvas width and height
      canvasWH: {
        width: 0,
        height: 0
      },
      // balls data
      balls: [],
      // ball cycle colors
      colors: [
        // #6ed4d3
        [110, 212, 211],
        // #f5738f
        [245, 115, 143],
        // #4bb7e4
        [75, 183, 228]
      ],
      // current color
      currentColor: {
        color: '',
        opacity: false
      },
      // draw frame time ms
      frameTime: 30,
      // config
      config: {
        // ball's total num
        ballNum: 30,
        // ball's max radius
        ballMaxRadius: 20,
        // ball's min radius
        ballMinRadius: 10,
        // two ball's max connect line distance
        maxConnectDis: 300,
        // max speed when ball move
        maxV: 2,
        // min speed when ball move
        minV: -2,
        // v scale
        vScale: 0.1,
        // balls max opacity
        maxOpacity: 0.5,
        // balls min opacity
        minOpacity: 0,
        // opacity v
        opacityV: 0.002
      }
    }
  },
  methods: {
    /**
     * @description             init canvas
     * @return     {undefined}  no return
     */
    initCanvas () {
      const { $refs, canvasWH } = this

      const canvas = $refs['canvas-bg']

      this.canvas = canvas

      this.ctx = canvas.getContext('2d')

      let [width, height] = []

      canvasWH.width = width = canvas.clientWidth
      canvasWH.height = height = canvas.clientHeight

      canvas.setAttribute('width', `${width}px`)
      canvas.setAttribute('height', `${height}px`)
    },
    /**
     * @description             do some action and start draw
     * @return     {undefined}  no return
     */
    start () {
      const { createBalls, draw } = this

      createBalls()

      draw()
    },
    /**
     * @description             create balls for draw
     * @return     {undefined}  no return
     */
    createBalls () {
      const { balls, config, canvasWH: { width, height } } = this

      const { ballNum, ballMinRadius, ballMaxRadius, maxV, minV, vScale } = config

      const { random } = this

      for (let i = 0; i < ballNum; i++) {
        let [vx, vy] = [random(minV, maxV), random(minV, maxV)]

        if (vx === 0 && vy === 0) {
          (i % 2 === 0) ? (vx = vy = 1) : (vx = vy = -1)
        }

        balls.push({
          x: random(0, width),
          y: random(0, height),
          radius: random(ballMinRadius, ballMaxRadius),
          vx: vx * vScale,
          vy: vy * vScale
        })
      }
    },
    /**
     * @description       get a random num between min and max
     * @return     {Int}  random num
     */
    random (min, max) {
      const { trunc, random } = Math

      return trunc(random() * (max - min + 1) + min)
    },
    /**
     * @description             main draw
     * @return     {undefined}  no return
     */
    draw () {
      const { clearCanvas, calcCurrentColor, drawBalls } = this

      clearCanvas()

      calcCurrentColor()

      drawBalls()

      const { drawConnectLines, calcBallsPosition, frameTime } = this

      drawConnectLines()

      calcBallsPosition()

      setTimeout(this.draw, frameTime)
    },
    /**
     * @description             clear canvas
     * @return     {undefined}
     */
    clearCanvas () {
      const { ctx, canvasWH: { width, height } } = this

      ctx.clearRect(0, 0, width, height)
    },
    /**
     * @description             calc current balls color
     * @return     {undefined}  no return
     */
    calcCurrentColor () {
      const { currentColor, config, colors } = this

      const { maxOpacity, minOpacity, opacityV } = config

      const { opacity } = currentColor

      const { random } = this

      if (opacity === false || opacity < minOpacity || opacity > maxOpacity) {
        const color = colors[random(0, colors.length - 1)]

        if (opacity === false) {
          currentColor.color = color
          currentColor.opacity = maxOpacity
          config.opacityV = Math.abs(opacityV) * -1
        }

        if (opacity < minOpacity) {
          currentColor.color = color
          currentColor.opacity = minOpacity
          config.opacityV = Math.abs(opacityV)
        }

        if (opacity > maxOpacity) {
          currentColor.opacity = maxOpacity
          config.opacityV = Math.abs(opacityV) * -1
        }
      } else {
        currentColor.opacity += opacityV
      }
    },
    /**
     * @description             draw balls
     * @return     {undefined}  no return
     */
    drawBalls () {
      const { balls, getCurrentColor, drawBall } = this

      const currentColor = getCurrentColor()

      balls.forEach(({x, y, radius}) => drawBall(x, y, radius, currentColor))
    },
    /**
     * @description          get current balls color
     * @return     {String}  color rgba
     */
    getCurrentColor () {
      const { currentColor: { color, opacity } } = this

      return `rgba(${color[0]}, ${color[1]}, ${color[2]}, ${opacity})`
    },
    /**
     * @description             draw ball
     * @return     {undefined}  no return
     */
    drawBall (x, y, radius, color) {
      const { ctx } = this

      ctx.beginPath()

      ctx.arc(x, y, radius, 0, Math.PI * 2)

      ctx.closePath()

      ctx.strokeStyle = color
      ctx.fillStyle = color

      ctx.stroke()
      ctx.fill()
    },
    /**
     * @description             draw balls connect lines
     * @return     {undefined}  no return
     */
    drawConnectLines () {
      const { balls, getCurrentColor, drawLine, config: { maxConnectDis } } = this

      const ballNum = balls.length

      const { abs, hypot } = Math

      const color = getCurrentColor()

      balls.forEach((a, i) => {
        balls.forEach((b, j) => {
          if (i + j >= ballNum) return

          const ballOne = balls[i]
          const ballTwo = balls[i + j]

          const absX = abs(ballOne.x - ballTwo.x)
          const absY = abs(ballOne.y - ballTwo.y)

          const lineDistance = hypot(absX, absY)

          if (lineDistance > maxConnectDis) return

          drawLine(ballOne, ballTwo, color)
        })
      })
    },
    /**
     * @description             calc balls position
     * @return     {undefined}  no return
     */
    calcBallsPosition () {
      const { balls, canvasWH: { width, height } } = this

      balls.forEach(({x, y, vx, vy}, index, array) => {
        let currentX = x + vx
        let currentY = y + vy

        currentX > width && (currentX = 0)
        currentX < 0 && (currentX = width)

        currentY > height && (currentY = 0)
        currentY < 0 && (currentY = height)

        array[index].x = currentX
        array[index].y = currentY
      })
    },
    /**
     * @description             draw two points connect line
     * @return     {undefined}  no return
     */
    drawLine ({x: bx, y: by}, {x: ex, y: ey}, color) {
      const { ctx } = this

      ctx.beginPath()

      ctx.moveTo(bx, by)
      ctx.lineTo(ex, ey)

      ctx.closePath()

      ctx.strokeStyle = color

      ctx.lineWidth = 0.3

      ctx.stroke()
    }
  },
  mounted () {
    const { initCanvas, start } = this

    initCanvas()

    start()
  }
}
</script>

<style lang="less">
#canvas-bg {
  position: absolute;
  top: 0px;
  left: 0px;
  width: 100%;
  height: 100%;
  z-index: -1;
  overflow: hidden;

  canvas {
    width: 100%;
    height: 100%;
  }
}
</style>
