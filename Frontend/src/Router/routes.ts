import { createBrowserRouter } from "react-router";
import App from '../App';
import PageLogin from "../Presentation/PageLogin";
import PageRegister from "../Presentation/PageRegister";

export const routes = createBrowserRouter([{
    path: '/',
    Component: App,
    children: [
        { path: '', index: true, Component: PageLogin },
        { path: 'register', Component:  PageRegister}
    ]
}])