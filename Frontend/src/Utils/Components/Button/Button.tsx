import type { FC } from 'react';
import { type IButtonProps } from '../types';
import './Button.css'

const Button: FC<IButtonProps> = ({type, textContent, className, icon, onClick})=>{

    return (
        <button 
            type={type ?? 'button'}
            onClick={onClick}
            className={ className + ' rounded-md  font-medium flex gap-3 justify-center'}            
        >
              {icon ? <img src={icon} alt="image-logo" /> : ''}  {textContent}
        </button>
    )
}

export default Button