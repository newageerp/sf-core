import React from 'react'
// import { UIConfig } from '../../index';
import { isMobile } from 'react-device-detect';
import { usePrint } from './OldTable';

export interface ToolbarProps {
    children: any,
    inline?: boolean
}

export default function OldToolbar(props: ToolbarProps) {
    const isPrint = usePrint();

    return (
        <div className={`${isPrint ? '' : ''}  ${props.inline ? '' : 'fixed top-0 right-0'} ${isMobile || isPrint ? "left-0" : " left-60 h-16 "}`} style={{ zIndex: 49 }}>
            <div className={`flex items-center h-16 px-4 gap-6`}>
                <div className={`flex-grow ${isMobile ? 'px-10' : ''}`}>
                    {props.children}
                </div>
                {/* {!isMobile && !isPrint && <Fragment>
                    <img src={"https://stressfreesolutions.lt/static/images/NaeLogo.png"} style={{ width: 120 }} />
                    {UIConfig.clientLogo && <img src={UIConfig.clientLogo} style={{ maxWidth: 120, maxHeight: 30 }} />}
                </Fragment>} */}

            </div>
        </div>
    )
}
