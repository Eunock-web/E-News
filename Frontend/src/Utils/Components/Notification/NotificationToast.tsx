import { CircleCheck, CircleX, TriangleAlert, X} from "lucide-react";
import type { FC } from "react"

export type NotificationType = 'success' | 'error' | 'warning'
export interface NotificationProps {
    type: NotificationType,
    message: string,
    id: string,
    close: ()=>void
}

const NotificationToast: FC<NotificationProps> = ({type, message, id, close}) => {
    let typeClass = '';
    let icon = null;
    switch(type){
        case 'success':
            typeClass = 'bg-green-100 text-white ';
            icon = CircleCheck;
            break;
        case 'error':
            typeClass = 'bg-red-100 text-red-500 ';
            icon = CircleX;
            break;
        case 'warning':
            typeClass = 'bg-yellow-100 text-white ';
            icon = TriangleAlert
    }   
  return (
        <div className={typeClass + 'font-semibold my-2'} id={id}>
            <X className='w-3 h-3 absolute right-1 top-1' onClick={close}></X>
            <span>{message}</span>
        </div>    
  )
}

export default NotificationToast