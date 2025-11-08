import { useCallback, type Dispatch, type SetStateAction } from 'react'
import type { NotificationProps, NotificationType } from '../NotificationToast'

const NOTIFICATION_TIME = 2000;

const useNotification = (notifications:NotificationProps[], setNotifications:Dispatch<SetStateAction<NotificationProps[]>>) => {
    
    const removeNotification = (id:string) => {
        const notification = document.getElementById(id);
        if(!notification) return;
        const animate = notification?.animate([
            {opacity: 0, transform: 'translateX(-200px)'}
        ], {duration: 1000, easing: 'ease-in-out', fill: 'forwards'});

        if(animate)
            setNotifications((prev:NotificationProps[]) => prev.filter(n => n.id !== notification.id))
    }

    const renderNotification = useCallback((message:string, type:NotificationType)=>{

        const newNotification: NotificationProps = {
            id: Date.now().toString(),
            type: type,
            message: message,
            close: ()=>removeNotification(newNotification.id)
        }

        setNotifications(prev => [...prev, newNotification])

        setTimeout(() => removeNotification(newNotification.id), NOTIFICATION_TIME)

    }, [removeNotification])

    

  return {
    render: renderNotification,
    notifications: notifications, 
    removeNotification
  }
}

export default useNotification