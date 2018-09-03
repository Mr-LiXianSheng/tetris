// development status
export const devMode = true

// export const devDevice = 'PC'
export const devDevice = 'MAC'

const oleWebsocketUrl = 'localhost:5921'

const pcDevWebsocketUrl = '192.168.10.150:5921'

const macDevWebsocketUrl = 'localhost:5922'

const websocketUrl = (devMode ? (devDevice === 'PC' ? pcDevWebsocketUrl : macDevWebsocketUrl) : oleWebsocketUrl)

// multiplayer tetris game base infomation
export const baseInfo = {
  name: 'multiplayer tetris',

  version: 'v_0.0.1',

  developer: 'JiaMing',

  websocketUrl
}
