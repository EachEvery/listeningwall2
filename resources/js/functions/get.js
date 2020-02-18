import Axios from "axios";

export default url => {
    return Axios.get(url);
};
