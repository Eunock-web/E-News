import { createBrowserRouter } from "react-router";
import App from '../App';
import AuthLayout from "../Presentation/Layout/AuthLayout";
import FormLogin from "../Features/Auth/Components/FormLogin";
import FormRegister from "../Features/Auth/Components/FormRegister";
import FormForgotPass from "../Features/Auth/Components/FormForgotPass";
import FormResetPass from "../Features/Auth/Components/FormResetPass";
import FormCategories from "../Features/Auth/Components/FormCategories";

export const routes = createBrowserRouter([{
    path: '',
    Component: App,
    children: [
        {
            path: '/', 
            Component: AuthLayout,
            children: [
                { path: 'login', index:true, Component: FormLogin},
                { path: 'register', Component:  FormRegister},
                { path:'forgot-password', Component: FormForgotPass },
                { path:'reset-password', Component: FormResetPass },
                { path: 'home', Component: AuthLayout},
                { path: 'categories', Component: FormCategories }
            ]
        }  
    ]
}])