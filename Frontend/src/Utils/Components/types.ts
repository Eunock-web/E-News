export interface ITextFieldProps{
    type: string,
    label: string, 
    className: string,
    placeholder: string, 
    errorMessage: string,
    onChange: () => void
}

export interface IButtonProps {
    type: 'button' | 'submit',
    textContent: string,
    className: string,
    icon: string,
    onClick: () => void
}