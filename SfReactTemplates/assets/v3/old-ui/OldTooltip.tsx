import React, { Fragment } from 'react'
import ReactTooltip from 'react-tooltip';
const md5 = require("md5");


interface Props {
    text: string,
    className?: string,
}

export default function OldTooltip(props: Props) {
    const { text, className } = props;
    const helpKey = md5(text);
    return (
        <Fragment>
            <span
                data-tip={text
                    .split("|||")
                    .map((e: any) => `<p>${e}</p>`)
                    .join("")}
                data-for={"view-" + helpKey}
                data-html={true}
            >
                <i className={"fad fa-info-circle text-blue-500 mr-1 " + (className ? className : "")} />
            </span>
            <ReactTooltip
                id={"view-" + helpKey}
                multiline={true}
                type={"light"}
                className={"rounded-md shadow-md text-left max-w-md"}
            />
        </Fragment>
    )
}
