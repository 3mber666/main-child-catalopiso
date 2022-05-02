import React, { Component } from "react";
import { Helmet } from "react-helmet";
import axios from "axios";
import QRCode from "qrcode";

function removeTrailingSlash(str) {
  return str.endsWith('/') ? str.slice(0, -1) : str;
}

class Settings extends Component {

  state = {
    shop_details: [],
    shop_details1: [],
    modalState: false,
    store_name: "",
    code: "",
    url: "",
    qrcode: "",
    logo: "",
    qrcode_data: "",
    openQR: false,
    message: false,
    displayCustomer: false,
  };

  closeModal = (e) => {
    e.preventDefault();
    this.setState({ modalState: false });
  };

  generateQR = (e) => {
    QRCode.toDataURL(removeTrailingSlash(window.location.origin) + "/qrcode?product=" + this.state.qrcode)
      .then((url) => {
        this.setState({ qrcode_data: url });
      })
      .catch((err) => {
        console.error(err);
      });
  };

  
  // handling data for QR-URL
  handleQRUrl = (e) => {
    e.preventDefault();
    let strurl = e.target.value;
    let url = new URL(strurl)
    let pathandQuery = url.pathname + url.search;
    console.log(pathandQuery);
    this.setState({ qrcode: pathandQuery });
  };

  // handling data for store_name
  handleNameChange = (e) => {
    e.preventDefault();
    this.setState({ store_name: e.target.value });
  };

  // handling data for code
  handleCodeChange = (e) => {
    e.preventDefault();
    this.setState({ code: e.target.value });
  };


  handleLogoChange = (e) => {
    e.preventDefault();
    this.setState({ logo: e.target.value });
  };

  // handling data for url
  handleUrlChange = (e) => {
    e.preventDefault();
    this.setState({ url: e.target.value });
  };

  openModal = (e) => {
    e.preventDefault();
    this.setState({ modalState: true });
  };

  openQrModal = (e) => {
    e.preventDefault();
    this.setState({ openQR: true });
  };

  closeQrModal = (e) => {
    e.preventDefault();
    this.setState({ openQR: false });
  };


  handleClick = (data, e) => {
    e.preventDefault();
      axios
        .post(
          `${appLocalizer.apiUrl}/wprk/v1/delete`,
          {
            store_id: data,
          },
          {
            headers: {
              "content-type": "application/json",
              "X-WP-NONCE": appLocalizer.nonce,
            },
          }
        )
        .then((res) => {
          window.location.reload();
        });
  };


  handleSubmit = (e) => {
    e.preventDefault();
    if (this.state.store_name && this.state.code && this.state.url && this.state.logo) {
      axios
        .post(
          `${appLocalizer.apiUrl}/wprk/v1/settings`,
          {
            test1: this.state.store_name,
            test2: this.state.code,
            test3: removeTrailingSlash(this.state.url),
            test4: this.state.logo,
          },
          {
            headers: {
              "content-type": "application/json",
              "X-WP-NONCE": appLocalizer.nonce,
            },
          }
        )
        .then((res) => {
          this.setState({ message: true });
          window.location.reload();
        });
    }
  };


handleUsersData = (data, e) => {
    e.preventDefault();
    axios.get(`${appLocalizer.apiUrl}/store/v1/users/${data}`).then((res) => {
      this.setState({ shop_details1: res.data });
      console.log(res.data);
      this.setState({ displayCustomer: true });
    });
  };

  

  componentDidMount() {
    axios.get(`${appLocalizer.apiUrl}/wprk/v1/settings`).then((res) => {
      this.setState({ shop_details: res.data });
    });


    axios.get(`${appLocalizer.apiUrl}/store/v1/users/23232`).then((res) => {
      this.setState({ shop_details1: res.data });
      console.log(res.data);
    });
    
  }

  render() {
    // render all current states
    const { 
        shop_details, 
        modalState, 
        store_name, 
        code, 
        url 
      } = this.state;

    // render all functions (modal, store: name, code, url)
    const {
        closeModal,
        openModal,
        handleCodeChange,
        handleNameChange,
        handleUrlChange
      } = this;

    return (
      <React.Fragment>
        <Helmet>
          <script src="https://cdn.tailwindcss.com"></script>
        </Helmet>
        <div class="lg:col-span-5 xl:col-span-6 flex flex-col">
          <div class="relative z-10 rounded-xl bg-white shadow-xl ring-1 ring-slate-900/5 overflow-hidden my-auto xl:mt-18 dark:bg-slate-800">
            <div
              id="authentication-modal"
              tabindex="-1"
              aria-hidden="false"
              class={`${
                this.state.openQR ? "" : "hidden"
              } overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center flex`}
            >
              <div class="relative mx-auto my-20 p-4 w-full max-w-md h-full md:h-auto">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                  <div class="flex justify-end p-2">
                    <button
                      onClick={this.closeQrModal}
                      type="button"
                      class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white"
                      data-modal-toggle="authentication-modal"
                    >
                      <svg
                        class="w-5 h-5"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          fill-rule="evenodd"
                          d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                          clip-rule="evenodd"
                        ></path>
                      </svg>
                    </button>
                  </div>
                  <div class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8">
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                      Generate QR Code
                    </h3>
                    <div>
                      <label
                        for="qrcode"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                      >
                        Enter the Product URL
                      </label>
                      <input
                        value={this.state.qrcode}
                        onChange={this.handleQRUrl}
                        type="text"
                        name="qrcode"
                        id="qrcode"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        placeholder="https://google.com"
                        required
                      />
                    </div>
                    <img
                      class="mx-auto"
                      src={this.state.qrcode_data ? this.state.qrcode_data : ""}
                    />
                    <button
                      onClick={this.generateQR}
                      type="submit"
                      class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >
                      Create QR Code
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div
              id="defaultModal"
              tabindex="-1"
              aria-hidden="true"
              class={`${
                modalState ? "" : "hidden"
              } overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center flex`}
            >
              <div class="relative mx-auto my-20 p-4 w-full max-w-md h-full md:h-auto">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                  <div class="flex justify-end p-2">
                    <button
                      onClick={closeModal}
                      type="button"
                      class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white"
                      data-modal-toggle="authentication-modal"
                    >
                      <svg
                        class="w-5 h-5"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          fill-rule="evenodd"
                          d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                          clip-rule="evenodd"
                        ></path>
                      </svg>
                    </button>
                  </div>
                  <form
                    class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8"
                    action="#"
                  >
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                      Add Store
                    </h3>
                    <div>
                      <label
                        for="store-name"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                      >
                        Store Logo
                      </label>
                      <input
                        value={this.state.logo}
                        onChange={this.handleLogoChange}
                        type="text"
                        name="logo"
                        id="logo"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Enter logo URL"
                        required
                      />
                    </div>
                    <div>
                      <label
                        for="store-name"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                      >
                        Store Name
                      </label>
                      <input
                        value={store_name}
                        onChange={handleNameChange}
                        type="text"
                        name="store-name"
                        id="store-name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Enter Store Name"
                        required
                      />
                    </div>
                    <div>
                      <label
                        for="code"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                      >
                        Code
                      </label>
                      <input
                        value={code}
                        onChange={handleCodeChange}
                        type="text"
                        name="code"
                        id="code"
                        placeholder="Enter Password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        required
                      />
                    </div>
                    <div>
                      <label
                        for="url"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                      >
                        URL
                      </label>
                      <input
                        value={url}
                        onChange={handleUrlChange}
                        type="text"
                        name="url"
                        id="url"
                        placeholder="Enter Store URL Address"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        required
                      />
                    </div>
                    <button
                      onClick={this.handleSubmit}
                      type="submit"
                      class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >
                      Add
                    </button>
                    <div class={`${this.state.message ? "" : "hidden"}`}>
                      {" "}
                      Successfully Added
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <section>
              <header class="rounded-t-xl space-y-4 p-4 sm:px-8 sm:py-6 lg:p-4 xl:px-8 xl:py-6 dark:highlight-white/10">
                <div class="flex items-center justify-between">
                  <h2 class="font-semibold text-slate-900 dark:text-white">
                    Active Stores
                  </h2>
                </div>
              </header>
              <ul class="bg-slate-50 p-4 sm:px-8 sm:pt-6 sm:pb-8 lg:p-4 xl:px-8 xl:pt-6 xl:pb-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-1 gap-4 text-sm leading-6 dark:bg-slate-900/40 dark:ring-1 dark:ring-white/5">
                {shop_details &&
                  shop_details.map((e, i) => 
                  (
                 ///   <a onClick={this.handleClick.bind(this, e.store_code)}>
                      <li class="group cursor-pointer rounded-md p-3 bg-white ring-1 ring-slate-200 shadow-sm hover:bg-blue-500 hover:ring-blue-500 hover:shadow-md dark:bg-slate-700 dark:ring-0 dark:highlight-white/10 dark:hover:bg-blue-500">
                        <dl class="grid sm:block lg:grid xl:block grid-cols-2 grid-rows-2 items-center">
                          <div>
                            <dt class="sr-only">{e.name} ─ Store</dt>
                            <dd class="font-semibold text-slate-900 group-hover:text-white dark:text-slate-100">
                              {e.store_name} ─ Store
                            </dd>
                          </div>
                          <div>
                            <dt class="sr-only">Category</dt>
                            <dd class="group-hover:text-blue-200">
                              Code:{" "}
                              <span class="px-1 py-0.5 font-semibold text-sm bg-white text-slate-700 dark:bg-slate-700 dark:text-white rounded-md shadow-sm ring-1 ring-slate-900/5 border-indigo-500 dark:border-sky-500 border-2 border-solid">
                                {e.store_code}
                              </span>
                            </dd>
                          </div>
                          <div class="col-start-2 row-start-1 row-end-3 sm:mt-4 lg:mt-0 xl:mt-4">
                            <dt class="sr-only">Users</dt>
                            <dd class="flex justify-end sm:justify-start lg:justify-end xl:justify-start -space-x-1.5">
                            <button onClick={this.handleUsersData.bind(this, e.store_code)} type="button" class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 mr-2 mb-2">
                            <svg width="10" class="w-4 h-4 mr-2 -ml-1" fill="currentColor" height="10" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M20.332 23h-6.999l-2.226-6.543c-.136-.279-.42-.457-.732-.457h-.375v-1h3.729l2.056-3.738c.106-.171.285-.262.467-.262.426 0 .691.469.467.834l-1.741 3.166h4.044l-1.741-3.166c-.224-.365.041-.834.467-.834.182 0 .361.091.467.262l2.056 3.738h3.729v1h-.374c-.312 0-.597.178-.733.459l-2.561 6.541zm-8.396-1h-11.936c0-.277-.002-2.552-.004-2.803-.008-2.111.083-3.319 2.514-3.88 2.663-.614 5.801-1.165 4.537-3.495-3.744-6.906-1.067-10.822 2.954-10.822 3.942 0 6.686 3.771 2.952 10.822l-1.091 2.178h-1.862c-.552 0-1 .448-1 1v1c0 .552.448 1 1 1h.236l1.7 5zm3.546-4.426c0-.276-.224-.5-.5-.5s-.5.224-.5.5v3c0 .276.224.5.5.5s.5-.224.5-.5v-3zm2-.074c0-.276-.224-.5-.5-.5s-.5.224-.5.5v3c0 .276.224.5.5.5s.5-.224.5-.5v-3zm2.036 0c0-.276-.224-.5-.5-.5s-.5.224-.5.5v3c0 .276.224.5.5.5s.5-.224.5-.5v-3z"/></svg>
                              <div class="invisible md:visible">View Today's Customer</div>
                              </button>
                              <a onClick={this.handleClick.bind(this, e.id)}>
                              <button type="button" class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 mr-2 mb-2">
                              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2 -ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm6 13h-12v-2h12v2z"/></svg>
                              <div class="invisible md:visible">Close Shop</div>
                              </button>
                              </a>
                            </dd>
                          </div>
                        </dl>
                      </li>
                 //     </a>
                  ))}
                {shop_details.length == 0 &&
                <li class="flex">
                  <div
                    onClick={openModal}
                    class="group w-full flex flex-col items-center justify-center rounded-md border-2 border-dashed border-slate-300 text-sm leading-6 text-slate-900 font-medium py-3 cursor-pointer hover:border-blue-500 hover:border-solid hover:bg-white hover:text-blue-500 dark:border-slate-700 dark:text-slate-100 dark:hover:border-blue-500 dark:hover:bg-transparent dark:hover:text-blue-500"
                  >
                    <svg
                      width="20"
                      height="20"
                      fill="currentColor"
                      class="mb-1 text-slate-400 group-hover:text-blue-500"
                    >
                      <path d="M10 5a1 1 0 0 1 1 1v3h3a1 1 0 1 1 0 2h-3v3a1 1 0 1 1-2 0v-3H6a1 1 0 1 1 0-2h3V6a1 1 0 0 1 1-1Z"></path>
                    </svg>
                    Add Store
                  </div>
                </li>
                }
              </ul>
              <div class={`${this.state.displayCustomer ? "" : "hidden"} p-4 bg-white rounded-lg border shadow-md sm:p-8 dark:bg-gray-800 dark:border-gray-700`}>
                <div class="flex justify-between items-center mb-4">
                  <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">Latest Customers</h5>
                  <a href="#" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">View all</a>
                </div>
   <div class="flow-root">
        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
        {this.state.shop_details1 && this.state.shop_details1.map((e, i) => (
            <li class="py-3 sm:py-4">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <img class="w-8 h-8 rounded-full" src="https://nwsid.net/wp-content/uploads/2015/05/dummy-profile-pic-300x300.png" alt="Neil image" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                            {e.name}
                        </p>
                        <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                            {e.email}
                            <br />
                            <span class="font-semibold">{e.phone}</span>
                        </p>
                    </div>
                    <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                        <a target="_blank" href={`${e.store_url}/project-boards/?key=${e.key}`} class="px-2 py-2 font-semibold text-sm bg-white text-slate-700 dark:bg-slate-700 dark:text-white rounded-md shadow-sm ring-1 ring-slate-900/5 border-indigo-500 dark:border-sky-500 border-2 border-solid">Open Project Board</a>
                    </div>
                </div>
            </li>
            ))}
        </ul>
   </div>
</div>
            </section>
          </div>
        </div>
      </React.Fragment>
    );
  }
}

export default Settings;
