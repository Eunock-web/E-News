
const BASEURL = 'http://127.0.0.1:8000/api/'; 


export async function apiFetch(url:string, method = 'GET', data?:object ): Promise<any>{
    const options: RequestInit = {
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer',
            'Accept': 'application/json'
        },
        method: method
    }

    if(data) {
        options.method = 'POST'
        options.body = JSON.stringify(data)
    }
    
    try {
        const res = await fetch(BASEURL + url, options).then((response) =>{
            if(!response.ok)
                throw "An error occured";

            return response.json()
        }
        ).catch(err => {
            console.error(err);          
            throw err;
        });        
        return res;
    }catch(error){
        return error;        
    }
}
    