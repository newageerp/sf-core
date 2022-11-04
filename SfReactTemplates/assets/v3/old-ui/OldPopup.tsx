import React, { Fragment } from 'react'
import { NaePopupProvider } from './OldPopupProvider';

interface Props {
    visible: boolean;
    toggleVisible: () => void;
    children: any
}

export default function OldPopup(props: Props) {
    const handleKeyDown = (event: any) => {
        // ESC
        if (event.keyCode === 27) {
            event.stopPropagation();
            props.toggleVisible();
        }
    };

    if (!props.visible) {
        return <Fragment />
    }

    return (
        <NaePopupProvider isPopup={true} onClose={props.toggleVisible}>
            <div className={"overscroll-none fixed inset-0 h-screen w-screen z-50"} onKeyDown={handleKeyDown} tabIndex={-1}>
                <div className={"h-screen py-8 px-8 relative overflow-auto"} style={{backgroundColor: 'rgb(248 250 252)'}}>
                    <div className={"fixed top-3 right-3"}>
                        <button onClick={props.toggleVisible}>
                            <i className={"fal fa-times-circle text-2xl text-nprimary"} />
                        </button>
                    </div>
                    {props.children}
                </div>
            </div>
        </NaePopupProvider>
    );
}
