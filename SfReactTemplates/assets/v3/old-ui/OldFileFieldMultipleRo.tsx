import classNames from 'classnames';
import React from 'react'
import FileFieldRo from './OldFileFieldRo';

interface Props {
    files: any,
    short?: boolean
}

export default function OldFileFieldMultipleRo(props: Props) {
    const { files } = props;

    return (
        <div className={classNames(
            'tw3-flex tw3-flex-col tw3-gap-1 tw3-items-center',
            { 'tw3-w-96': !props.short },
            'tw3-rounded-md tw3-border tw3-border-gray-300',
            'tw3-px-2 tw3-py-1'
        )}>
            {files.map((f: any, fIndex: number) => {
                return (
                    <FileFieldRo
                        short={props.short}
                        otherFiles={files}
                        width={props.short ? undefined : "tw3-w-full"}
                        file={f}
                        key={"file-" + fIndex} />
                );
            })}
        </div>
    )
}
