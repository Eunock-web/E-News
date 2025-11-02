import { createBrowserRouter } from "react-router";
import App from '../App';
import LoginPresentation from "../Presentation/LoginPage";

export const routes = createBrowserRouter([{
    path: '/',
    Component: App,
    children: [
        { path: '', index: true, Component: LoginPresentation }
    ]
}])