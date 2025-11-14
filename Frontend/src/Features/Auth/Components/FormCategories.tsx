import useStoreDefault from "../../store"

const FormCategories = () => {
    const categories = useStoreDefault((cat)=>cat.);
  return (
    <form action="">
        <h1>Choose some categories of news you want your feed to be full of.</h1>
        <ul>

        </ul>
    </form>
  )
}

export default FormCategories