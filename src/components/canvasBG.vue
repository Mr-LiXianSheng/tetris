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
      colors: [],
      // current color
      currentColor: '',
      // draw frame time ms
      frameTime: 30,
      // config
      config: {
        // ball's total num
        ballNum: 30,
        // ball's max radius
        ballMaxRadius: 20,
        // ball's min radius
        ballMinRadius: 5,
        // two ball's max connect line distance
        maxConnectDis: 200
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
     * @description             main draw
     * @return     {undefined}  no return
     */
    draw () {
      const { clearCanvas } = this

      clearCanvas()
    },
    /**
     * @description             clear canvas
     * @return     {undefined}
     */
    clearCanvas () {
      const { ctx, canvasWH: { width, height } } = this

      ctx.clearRect(0, 0, width, height)
    }
  },
  mounted () {
    const { initCanvas, draw } = this

    initCanvas()

    draw()
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
