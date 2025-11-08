import { createContext, useState, type FC, type ReactNode } from 'react';
import type { NotificationProps } from "./NotificationToast";
import useNotification from "./hooks/useNotification";
import type { NotificationManager } from './hooks/useNotificationManager';

export const NotificationContext = createContext<undefined| NotificationManager>(undefined);

const NotificationProvider: FC<{children: ReactNode}> = ({children}) => {
    const [notifications, setNotifications] = useState<NotificationProps[]>([]);
    const {render, removeNotification} = useNotification(notifications, setNotifications);
    const context = {notifications, render, removeNotification}
  return (
    <NotificationContext.Provider value={context}>
        {children}
    </NotificationContext.Provider>
  )
}

export default NotificationProvider
