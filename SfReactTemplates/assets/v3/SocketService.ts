// @ts-nocheck
import io from 'socket.io-client'

export class SocketServiceClass {
  private socket: any

  constructor() {
    this._connected = false
    this.callbacks = {}
    this.subscribes = []
  }

  addCallbackEntity = (
    schema: string,
    elementId: number,
    key: string,
    callback
  ) => {
    this.addCallback('entity-' + schema + '-' + elementId, key, callback)
  }
  removeCallbackEntity = (schema: string, elementId: number, key: string) => {
    this.removeCallback('entity-' + schema + '-' + elementId, key)
  }

  addCallback = (action, key, callback) => {
    if (!this.callbacks[action]) {
      this.callbacks[action] = {}
    }
    this.callbacks[action][key] = callback
  }

  removeCallback = (action, key) => {
    if (this.callbacks[action][key]) {
      delete this.callbacks[action][key]
    }
  }

  private _connected: boolean

  get connected(): boolean {
    return this._connected
  }

  connect = () => {
    this.socket = io.connect('/', {
      transports: ['websocket'],
      reconnectionAttempts: 15,
      // pingTimeout: 30000,
      timeout: 10000,
      jsonp: false,
      autoConnect: true,
      agent: false,
      pfx: '-',
      key: '-',
      passphrase: '-',
      cert: '-',
      ca: '-',
      ciphers: '-',
      rejectUnauthorized: false
    })

    this.socket.on('connect', () => {
      this._connected = true
      console.log('SOCKET', 'connect')

      this.subscribe('all')

      if (this.subscribes.length > 0) {
        this.subscribes.forEach((room: string) => {
          if (room !== 'all') {
            this.subscribe(room)
          }
        })
      }
    })

    this.socket.on('connect_error', (err: any) => {
      // console.log("SOCKET", "connect_error", err);
      console.log('SOCKET', 'connect_error', err)
    })
    this.socket.on('reconnect', (attempt: number) => {
      console.log('SOCKET', 'reconnect')
    })

    this.socket.on('reconnect_error', () => {
      // console.log("SOCKET", "reconnect_error");
      console.log('SOCKET', 'reconnect_error')
      this._connected = false
    })
    this.socket.on('disconnect', () => {
      console.log('SOCKET', 'disconnect')
      this._connected = false
    })

    this.socket.on('message', this.onMessage)
  }

  unsubscribe = (room: string) => {
    this.subscribes = this.subscribes.filter((r: string) => r !== room)
    this.socket.emit('unsubscribe', room)
  }

  subscribeToList = (payload, callback) => {
    if (this.socket && this._connected) {
      this.socket.emit('subscribeToList', payload)
      this.addCallback(payload.key, payload.key, callback)
    }
  }
  unSubscribeFromList = (payload) => {
    if (this.socket && this._connected) {
      this.socket.emit('unsubscribeFromList', payload)
    }
  }

  subscribe = (room: string) => {
    console.log('socket s', room, this._connected)
    if (this.socket) {
      if (this._connected) {
        this.socket.emit('subscribe', room)
        if (this.subscribes.indexOf(room) === -1) {
          this.subscribes.push(room)
        }
      } else {
        setTimeout(() => {
          this.subscribe(room)
        }, 300)
      }
    }
  }

  onMessage = (data: any) => {
    // console.log('onMessage', data)
    if (this.callbacks[data.action]) {
      Object.keys(this.callbacks[data.action]).forEach((k) => {
        const f = this.callbacks[data.action][k]
        // console.log('onMessage', data, k)
        if (f) {
          // setTimeout(() => {
          f(data.data)
          // }, 300);
        }
      })
    }
    // switch (data.action) {
    //     case 'funnel-order-updated':
    //         store.dispatch({type: "ORDERS_UPDATE_SUCCEEDED", data: data.data});
    //         break;
    //     default:
    //         break;
    // }
  }
}

const SocketService = new SocketServiceClass()
window.SocketService = SocketService
export default SocketService
