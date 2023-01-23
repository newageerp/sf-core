import React, { Fragment } from 'react'
import { ToolbarButton } from '@newageerp/v3.bundles.buttons-bundle'
interface Props {
  file: any
  width?: string
  otherFiles?: any[],
  short?: boolean
}

export default function OldFileFieldRo(props: Props) {
  const { file } = props;

  let ext = ''
  if (file && file.filename) {
    const _name = file.filename.split('.')
    ext = _name[_name.length - 1]
  }

  let width = 'tw3-w-auto tw3-max-w-96';
  if (props.short) {
    width = 'tw3-w-auto';
  }
  if (props.width) {
    width = props.width;
  }

  const openFile = () => {
    const options = {
      file: {
        title: file.filename,
        onView: {
          link: getViewLinkForFile(file),
          ext: ext,
          id: file.id ? file.id : 'file-' + file.filename,
        },
        onDownload: () => {
          const link = getLinkForFile(file)
          window.open(link, '_blank')
        }
      },
      otherFiles: props.otherFiles
        ? props.otherFiles.map((file, fIndex: number) => ({
          title: file.filename,
          onView: {
            link: getViewLinkForFile(file),
            ext: ext,
            id: file.id ? file.id : 'file-' + file.filename,
          },
          onDownload: () => {
            const link = getLinkForFile(file)
            window.open(link, '_blank')
          }
        })
        )
        : undefined
    }
    const event = new CustomEvent(
      'SFSOpenFilePreview',
      {
        detail: options
      }
    );
    window.dispatchEvent(event);
  };

  return (
    <div
      className={`tw3-flex tw3-gap-2 tw3-items-center ${width}`}
      title={file.filename}
    >
      <Fragment>
        {!props.short &&
          <p className={'tw3-flex-grow tw3-truncate'}>{file.filename}</p>
        }

        <ToolbarButton
          iconName='eye'
          onClick={openFile}
        />
        <ToolbarButton
          iconName='download'
          onClick={() => {
            const link = getLinkForFile(file)
            window.open(link, '_blank')
          }}
        />
      </Fragment>
    </div>
  )
}


export const getLinkForFile = (f: any): string => {
  if (f.path.indexOf('http://') === 0 || f.path.indexOf('https://') === 0) {
    return f.path;
  }
  return (
    window.location.origin +
    '/app/nae-core/files/download?f=' +
    encodeURIComponent(
      JSON.stringify({
        path: f.path,
        name: f.filename
      })
    ) +
    '&token=' +
    window.localStorage.getItem('token')
  )
}

export const getViewLinkForFile = (f: any): string => {
  if (f.path.indexOf('http://') === 0 || f.path.indexOf('https://') === 0) {
    return f.path;
  }
  return (
    window.location.origin +
    '/app/nae-core/files/view?f=' +
    encodeURIComponent(
      JSON.stringify({
        path: f.path,
        name: f.filename
      })
    ) +
    '&token=' +
    window.localStorage.getItem('token')
  )
}