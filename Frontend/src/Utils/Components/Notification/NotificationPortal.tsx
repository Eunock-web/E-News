import type { FC, ReactNode } from "react"
import { createPortal } from "react-dom";

interface NotificationPortalProps{
    children: ReactNode
}
const NotificationPortal:FC<NotificationPortalProps> = ( {children} ) => {
    const notificationRoot = document.getElementById('notification-root');
    if(!notificationRoot){
        console.warn("Notification root manquant pour l'affichage!")
        return null
    }
        
  return createPortal(children, notificationRoot)
}

export default NotificationPortal