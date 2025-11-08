import { useContext } from 'react'
import type { NotificationProps, NotificationType } from '../NotificationToast'
import { NotificationContext } from '../NotificationProvider'

export interface NotificationManager {
    notifications: NotificationProps[],
    render: (message:string, type: NotificationType) => void,
    removeNotification: (id:string) => void
}

const useNotificationManager = () => {
    const context = useContext(NotificationContext);
    if(context === undefined)
        throw new Error("Context is required!")
  return context
}

export default useNotificationManager