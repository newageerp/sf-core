import React, { useEffect, useState } from 'react'

import { useTranslation } from 'react-i18next'
import Button, { ButtonSize, ButtonBgColor } from './OldButton'
import TextAreaField from './OldTextarea'

import { getLinkForFile } from './OldFileFieldRo'
import FileUploadWidget from './OldFileUploadWidget'
import { showSuccessNotification, showErrorNotification } from '../navigation/NavigationComponent';
import { OpenApi } from '@newageerp/nae-react-auth-wrapper'
import { WhiteCard } from '@newageerp/v3.widgets.white-card'
import OldFormLabel from './OldFormLabel'
import OldInputField from './OldInputField'
import { RichEditor } from '@newageerp/ui.form.base.form-pack';
import { OldUiH5 } from './OldUiH5'
export interface EmailSuggest {
  name: string
  email: string
}

interface EmailFile {
  name: string
  link: string
}

interface PropsDefault {
  subject?: string
  recipients?: string
  content?: string
  signature?: string,

  recipientsSuggest?: EmailSuggest[]
}

interface Props {
  default?: PropsDefault
  extraData?: any

  onSend?: (res: any) => void

  files?: EmailFile[]
}

export default function OldEmailForm(props: Props) {
  const { t } = useTranslation()

  const defaultVars = props.default ? props.default : {}

  const [files, setFiles] = React.useState(props.files ? props.files : [])

  const [subject, setSubject] = React.useState(
    defaultVars.subject ? defaultVars.subject : ''
  )
  const [recipients, setRecipients] = React.useState(
    defaultVars.recipients ? defaultVars.recipients : ''
  )
  const [content, setContent] = React.useState(
    defaultVars.content ? defaultVars.content : ''
  )
  const [signature, setSignature] = React.useState(
    defaultVars.signature ? defaultVars.signature : ''
  )

  const isEmailUsed = (email: string) => {
    const emails = recipients.replaceAll(' ', '').split(',')
    return emails.indexOf(email) !== -1
  }

  const [recipientsSuggests, setRecipientsSuggests] = useState(
    defaultVars.recipientsSuggest ? defaultVars.recipientsSuggest : []
  )
  const addRecipient = (email: string) => {
    const emails = recipients.replaceAll(' ', '').split(',')
    if (emails.length === 1 && emails[0] === '') {
      setRecipients(email)
    } else {
      if (!isEmailUsed(email)) {
        emails.push(email)
      }
      setRecipients(emails.join(','))
    }
  }

  const emailHint = t(
    "Norint įvesti keletas email'ų veskite per kablelį|||(pvz. vardenis@pastas.lt, pavardenis@pastas.lt)"
  )

  const [sendData, sendDataParams] = OpenApi.useURequest('NAEmailsSend')
  const doSend = () => {
    sendData({
      subject,
      recipients,
      content: content + signature,
      extraData: props.extraData,
      files
    }).then((response: any) => {
      if (response && response.status === 200) {
        showSuccessNotification(t('Išsiųsta'))
        if (props.onSend) {
          props.onSend(response)
        }
      } else {
        showErrorNotification(t('Klaida'))
      }
    })
  }

  const [getMailData] = OpenApi.useURequest('NAEmailsGetData')
  useEffect(() => {
    getMailData({
      ...props.extraData,
      current: {
        subject,
        recipients,
        content,
        files
      }
    }).then((res: any) => {
      if (res.data) {
        const data = res.data
        if (data.subject) {
          setSubject(data.subject)
        }
        if (data.recipients) {
          setRecipients(data.recipients)
        }
        if (data.content) {
          setContent(data.content)
        }
        if (data.recipientsSuggest) {
          setRecipientsSuggests(data.recipientsSuggest)
        }
        if (data.files) {
          setFiles(data.files)
        }
        if (data.signature) {
          setSignature(data.signature);
        }
      }
    })
  }, [props.extraData])

  const doDownload = (url: string) => {
    window.open(url)
  }

  // const parseHtml = (html : string) => new DOMParser().parseFromString(html, 'text/html').body.innerText;


  return (
    <div className={'flex gap-2 px-52 py-4'}>
      <WhiteCard className={'flex-grow'}>
        <div className={'flex space-x-2 items-center'}>
          <OldFormLabel
            className={'text-gray-700 w-56'}
            text={t('Tema')}
            required={true}
          />
          <span className={'flex-grow'}>
            <OldInputField
              className={'w-96'}
              value={subject}
              onChange={(e) => setSubject(e.target.value)}
            />
          </span>
        </div>
        <div className={'flex space-x-2 items-center'}>
          <OldFormLabel
            help={emailHint}
            className={'text-gray-700 w-56'}
            text={t('Gavėjas')}
            required={true}
          />
          <span className={'flex-grow'}>
            <TextAreaField
              className={'w-full'}
              rows={1}
              autoRows={true}
              value={recipients}
              onChange={(e) => setRecipients(e.target.value)}
            />
          </span>
        </div>
        {!!recipientsSuggests && recipientsSuggests.length > 0 && (
          <div className={'flex flex-wrap gap-2'}>
            {recipientsSuggests.map((e: EmailSuggest, index: number) => {
              const isUsed = isEmailUsed(e.email)
              return (
                <Button
                  key={'recipient-' + index}
                  size={ButtonSize.sm}
                  bgColor={isUsed ? ButtonBgColor.blue : ButtonBgColor.gray}
                  brightness={100}
                  className={'shadow-none'}
                  onClick={() => addRecipient(e.email)}
                >
                  {e.name} ({e.email})
                </Button>
              )
            })}
          </div>
        )}
        <div>
          <OldFormLabel
            className={'text-gray-700 w-56'}
            text={t('Turinys')}
            required={true}
          />
          <RichEditor value={content} setValue={setContent} />

          {signature &&
            <iframe className='w-full' style={{height: 400}} srcDoc={signature} />
          }
        </div>
      </WhiteCard>
      <div className={'w-80'} style={{ minWidth: '20rem' }}>
        <div className={'grid grid-cols-1 gap-1'}>
          <Button
            onClick={doSend}
            iconLoading={sendDataParams.loading}
            icon={'fad fa-paper-plane'}
          >
            {t('Siųsti')}
          </Button>

          <WhiteCard>
            <OldUiH5 icon={'fad fa-link'}>{t('Prisegti failai')}</OldUiH5>
            <div className={"space-y-1"}>
              {files.map((f: EmailFile, index: number) => {
                return (
                  <div
                    className={'flex items-center'}
                    key={'mail-file' + index}
                  >
                    <div className={'flex-grow text-sm'}>{f.name}</div>
                    <div className={'grid gap-1 grid-flow-col'}>
                      <Button
                        bgColor={ButtonBgColor.blue}
                        brightness={100}
                        icon={'fal fa-download'}
                        onClick={() => doDownload(f.link)}
                      />

                      <Button
                        bgColor={ButtonBgColor.blue}
                        brightness={100}
                        icon={'fal fa-times'}
                        onClick={() =>
                          setFiles([
                            ...files.filter((_f) => f.link !== _f.link)
                          ])
                        }
                      />
                    </div>
                  </div>
                )
              })}
            </div>
          </WhiteCard>

          <FileUploadWidget
            type={'email-form'}
            onUpload={(nfiles) => {
              Object.keys(nfiles).map((k: string) => {
                const f = nfiles[k]
                const link = getLinkForFile(f)
                setFiles([
                  ...files,
                  {
                    name: f.filename,
                    link: link
                  }
                ])
              })
            }}
          />
        </div>
      </div>
    </div>
  )
}
